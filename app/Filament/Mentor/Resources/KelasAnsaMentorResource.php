<?php

namespace App\Filament\Mentor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Program;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\KelasAnsaMentor;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Mentor\Resources\KelasAnsaMentorResource\Pages;
use App\Filament\Mentor\Resources\KelasAnsaMentorResource\RelationManagers;

class KelasAnsaMentorResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kelas ANSA';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
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
            ->query(Program::query()->with(['kelasAnsaDetail'])->whereProgram('Kelas ANSA')->whereHas('mentors', function($query)
                {
                    $query->whereId(auth()->id());
                })
                ->latest())
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->collection('program-thumbnail'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('masukLinkMeet')
                    ->label('Link Meet')
                    ->icon('heroicon-o-link')
                    ->url(fn(Program $program) => $program->kelasAnsaDetail->link_meet)
                    ->openUrlInNewTab(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageKelasAnsaMentors::route('/'),
        ];
    }
}
