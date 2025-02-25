<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Mentor;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use App\Models\ProdukDigital;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\MentorResource\Pages;
use App\Filament\Admin\Resources\MentorResource\RelationManagers;

class MentorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Mentor';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $model): bool
    {
        return false;
    }

    // public static function canEdit(Model $record): bool
    // {
    //     return false;
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->whereHas('roles', function(Builder $query) {
                $query->where('name', 'mentor');
            }))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Mentor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Foto Profil'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->label('Poster Mentor')
                    ->collection('mentor-poster'),
                Tables\Columns\TextColumn::make('profit')
                    ->label('Pendapatan Produk Digital')
                    ->weight(FontWeight::Bold)
                    ->getStateUsing(function(User $user)
                    {
                        $profit = Transaksi::where('status', 'Sukses')
                            ->where('transaksiable_type', ProdukDigital::class)
                            ->whereIn('transaksiable_id', function($query) use ($user) {
                                $query->select('id')
                                    ->from('produk_digitals')
                                    ->where('mentor_id', $user->id);
                            })
                            ->sum('total_harga') * 0.6;

                        return 'Rp' . number_format($profit, 0, ',', '.');
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Poster')
                    ->form([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('poster')
                            ->collection('mentor-poster')
                            ->maxFiles(1)
                            ->maxSize(1024)
                            ->required()
                            ->image()
                    ]),
                Tables\Actions\Action::make('lihatTestimoni')
                    ->label('Lihat Testimoni')
                    ->icon('heroicon-o-star')
                    ->url(fn (User $record) => Pages\TestimoniMentor::getUrl(['record' => $record])),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMentors::route('/'),
            'jadwal' => Pages\TestimoniMentor::route('/{record}/jadwal'),
        ];
    }
}
