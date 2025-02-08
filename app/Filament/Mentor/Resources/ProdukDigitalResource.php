<?php

namespace App\Filament\Mentor\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProdukDigital;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Mentor\Resources\ProdukDigitalResource\Pages;
use App\Filament\Mentor\Resources\ProdukDigitalResource\RelationManagers;

class ProdukDigitalResource extends Resource
{
    protected static ?string $model = ProdukDigital::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Produk Digital';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Detail Produk')
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(191),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                            ->required()
                            ->image()
                            ->maxFiles(1)
                            ->maxSize(1024)
                            ->collection('produk-digital-thumbnail'),
                        Forms\Components\RichEditor::make('deskripsi')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('platform')
                            ->required()
                            ->live()
                            ->options([
                                'file' => 'File',
                                'url' => 'URL',
                            ]),
                        Forms\Components\TextInput::make('url')
                            ->maxLength(191)
                            ->url()
                            ->required(fn(Get $get) => $get('platform') === 'url')
                            ->live()
                            ->visible(fn(Get $get) => $get('platform') === 'url'),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('file')
                            ->label('File Produk (PDF, DOCX, XLSX, PPTX, ZIP) Max 50MB')
                            ->required(fn(Get $get) => $get('platform') === 'file')
                            ->maxFiles(1)
                            ->maxSize(50000)
                            ->collection('produk-digital-file')
                            ->live()
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip'])
                            ->visible(fn(Get $get) => $get('platform') === 'file'),
                            ]),

                Forms\Components\Fieldset::make('Harga & Stok')
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('harga')
                            ->required()
                            ->prefix('Rp')
                            ->suffix(',00')
                            ->numeric(),
                        Forms\Components\Toggle::make('is_unlimited')
                            ->label('Stok Tidak Terbatas')
                            ->required()
                            ->live()
                            ->default(true),
                        Forms\Components\TextInput::make('qty')
                            ->numeric()
                            ->live()
                            ->visible(fn(Get $get) => !$get('is_unlimited'))
                            ->required(fn(Get $get) => !$get('is_unlimited')),
                        Forms\Components\Hidden::make('mentor_id')
                            ->default(auth()->id())
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(ProdukDigital::with(['media', 'mentor'])->whereHas('mentor', fn($query) => $query->where('mentor_id', auth()->id())))
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->collection('produk-digital-thumbnail'),
                Tables\Columns\TextColumn::make('platform')
                    ->getStateUsing(fn(ProdukDigital $produkDigital) => $produkDigital->platform === 'file' ? 'File' : 'URL')
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('harga')
                    ->numeric()
                    ->weight(FontWeight::Bold)
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Stok')
                    ->getStateUsing(fn(ProdukDigital $produkDigital) => $produkDigital->is_unlimited ? 'Unlimited' : $produkDigital->qty)
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('ketersediaan')
                    ->getStateUsing(fn(ProdukDigital $produkDigital) => $produkDigital->qty !== 0 ? 'Tersedia' : 'Tidak Tersedia')
                    ->badge()
                    ->color(fn(ProdukDigital $produkDigital) => $produkDigital->qty !== 0 ? 'success' : 'danger'),
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
                Filter::make('ketersediaan')
                    ->form([
                        Forms\Components\Select::make('ketersediaan')
                            ->options([
                                'tersedia' => 'Tersedia',
                                'tidak-tersedia' => 'Tidak Tersedia',
                            ])
                            ->label('Ketersediaan'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['ketersediaan'] === 'tersedia',
                                fn(Builder $query) => $query->where('qty', '>', 0)->orWhere('is_unlimited', true)
                            )
                            ->when(
                                $data['ketersediaan'] === 'tidak-tersedia',
                                fn(Builder $query) => $query->where('qty', 0)
                            );
                    }),
                ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageProdukDigitals::route('/'),
        ];
    }
}
