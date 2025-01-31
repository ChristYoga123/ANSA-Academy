<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MentorResource\Pages;
use App\Filament\Admin\Resources\MentorResource\RelationManagers;
use App\Models\Mentor;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MentorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Mentor';

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
            'index' => Pages\ManageMentors::route('/'),
        ];
    }
}
