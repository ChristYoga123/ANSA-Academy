<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LokerMentorBidangResource\Pages;
use App\Filament\Admin\Resources\LokerMentorBidangResource\RelationManagers;
use App\Models\LokerMentorBidang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LokerMentorBidangResource extends Resource
{
    protected static ?string $model = LokerMentorBidang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Bidang Loker Mentor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Toggle::make('is_buka')
                            ->label('Sedang Dibuka')
                            ->default(false),
                        Forms\Components\Repeater::make('kualifikasi')
                            ->label('Kualifikasi Bidang')
                            ->relationship('lokerMentorBidangKualifikasi')
                            ->schema([
                                Forms\Components\Textarea::make('kualifikasi')
                                    ->required(),
                            ])
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_buka')
                    ->label('Sedang Dibuka'),
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
            'index' => Pages\ManageLokerMentorBidangs::route('/'),
        ];
    }
}
