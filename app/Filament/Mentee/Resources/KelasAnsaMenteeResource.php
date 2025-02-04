<?php

namespace App\Filament\Mentee\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProgramMentee;
use App\Models\KelasAnsaMentee;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Mentee\Resources\KelasAnsaMenteeResource\Pages;
use App\Filament\Mentee\Resources\KelasAnsaMenteeResource\RelationManagers;

class KelasAnsaMenteeResource extends Resource
{
    protected static ?string $model = ProgramMentee::class;

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
            ->query(ProgramMentee::query()->with(['paketable', 'program.kelasAnsaDetail', 'program.media'])->whereMenteeId(auth()->id())->whereHas('program', function($query)
            {
                $query->whereProgram('Kelas ANSA');
            })
            ->latest())
            ->columns([
                Tables\Columns\TextColumn::make('nama_program')
                    ->label('Program')
                    ->weight(FontWeight::SemiBold)
                    ->getStateUsing(fn(ProgramMentee $programMentee) => $programMentee->program->judul . ' - ' . $programMentee->paketable->label),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->getStateUsing(fn(ProgramMentee $programMentee) => $programMentee->program->getFirstMediaUrl('program-thumbnail')),
                Tables\Columns\TextColumn::make('is_aktif')
                    ->label('Status')
                    ->badge()
                    ->color(fn(ProgramMentee $programMentee) => $programMentee->is_aktif ? 'success' : 'danger')
                    ->getStateUsing(fn(ProgramMentee $programMentee) => $programMentee->is_aktif ? 'Aktif' : 'Tidak Aktif'),
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
                Tables\Actions\Action::make('masukLinkMeet')
                    ->label('Link Meet')
                    ->icon('heroicon-o-link')
                    ->url(fn(ProgramMentee $programMentee) => $programMentee->program->kelasAnsaDetail->link_meet)
                    ->visible(fn(ProgramMentee $programMentee) => $programMentee->is_aktif)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('downloadResource')
                    ->label('Download Resource')
                    ->icon('heroicon-o-link')
                    ->url(fn(ProgramMentee $programMentee) => $programMentee->paketable->link_resource)
                    ->visible(fn(ProgramMentee $programMentee) => $programMentee->is_aktif && $programMentee->paketable->link_resource)
                    ->openUrlInNewTab(),
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
            'index' => Pages\ManageKelasAnsaMentees::route('/'),
        ];
    }
}
