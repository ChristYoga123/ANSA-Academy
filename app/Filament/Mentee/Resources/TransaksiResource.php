<?php

namespace App\Filament\Mentee\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Event;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use App\Models\ProdukDigital;
use App\Models\ProgramMentee;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Mentee\Resources\TransaksiResource\Pages;
use App\Filament\Mentee\Resources\TransaksiResource\RelationManagers;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Transaksi';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('order_id')
                //     ->required()
                //     ->maxLength(191),
                // Forms\Components\TextInput::make('mentee_id')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('transaksiable_type')
                //     ->required()
                //     ->maxLength(191),
                // Forms\Components\TextInput::make('transaksiable_id')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('referral_code')
                //     ->maxLength(191),
                // Forms\Components\TextInput::make('total_harga')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('status')
                //     ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->with(['transaksiable' => function ($morphTo) {
                    $morphTo->morphWith([
                        Event::class => [], // Tidak perlu relasi tambahan jika hanya akses atribut
                        ProdukDigital::class => [], // Uncomment dan pastikan relasi benar
                        ProgramMentee::class => ['program', 'paketable'], // Uncomment dan pastikan relasi benar
                        // KelasAnsaMentee::class => ['kelasAnsa'],
                        // ProofreadingMentee::class => ['proofreadingPaket.proofreading'],
                    ]);
                }])
                ->whereMenteeId(auth()->id())
                ->latest();
            })
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('mentee.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaksiable_id')
                    // ->searchable()
                    ->label('Judul')
                    ->getStateUsing(fn(Transaksi $transaksi) => match($transaksi->transaksiable_type) {
                        Event::class => $transaksi->transaksiable->judul,
                        ProdukDigital::class => $transaksi->transaksiable->judul,
                        ProgramMentee::class => $transaksi->transaksiable->program->judul . ' - ' . $transaksi->transaksiable->paketable->label,
                        // KelasAnsaMentee::class => $transaksi->transaksiable->kelasAnsa->judul,
                        // ProofreadingMentee::class => $transaksi->transaksiable->proofreadingPaket->proofreading->judul,
                    }),
                Tables\Columns\TextColumn::make('referral_code')
                    ->searchable()
                    ->getStateUsing(fn(Transaksi $transaksi) => $transaksi->referral_code ?? 'N/A'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->numeric()
                    ->money('IDR')
                    ->weight(FontWeight::Bold)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'Menunggu' => 'warning',
                        'Sukses' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('lihatProduk')
                    ->label('Download Produk')
                    ->url(fn(Transaksi $transaksi) => match($transaksi->transaksiable->platform)
                    {
                        'url' => $transaksi->transaksiable->url,
                        'file' => $transaksi->transaksiable->getFirstMediaUrl('produk-digital-file')
                    })
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn(Transaksi $transaksi) => $transaksi->transaksiable_type === ProdukDigital::class && $transaksi->status === 'Sukses'),
                Tables\Actions\Action::make('lihatResourceEvent')
                    ->label('Download Resource Event')
                    ->url(fn(Transaksi $transaksi) => $transaksi->transaksiable->link_resource)
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn(Transaksi $transaksi) => ($transaksi->transaksiable_type === Event::class && $transaksi->transaksiable->link_resource) && $transaksi->status === 'Sukses'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTransaksis::route('/'),
        ];
    }
}
