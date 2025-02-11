<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Mentee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\MenteeResource\Pages;
use App\Filament\Admin\Resources\MenteeResource\RelationManagers;

class MenteeResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Mentee';

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
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->whereHas('roles', function(Builder $query) {
                $query->where('name', 'mentee');
            }))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Mentee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('no_hp')
                    ->label('No. HP')
                    ->getStateUsing(fn(User $user) => $user->custom_fields['no_hp']),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->getStateUsing(fn(User $user) => $user?->custom_fields['alamat'] ?? '-'),
                ImageColumn::make('avatar_url')
                    ->label('Foto Profil')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('waMentee')
                    ->label('Hubungi')
                    ->icon('heroicon-o-phone')
                    ->url(fn(User $user) => 'https://wa.me/' . trim($user->custom_fields['no_hp'], '+'))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ManageMentees::route('/'),
        ];
    }
}
