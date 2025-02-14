<?php

namespace App\Filament\Mentor\Resources;

use App\Filament\Mentor\Resources\MentoringMentorResource\Pages;
use App\Filament\Mentor\Resources\MentoringMentorResource\RelationManagers;
use App\Models\MentoringMentor;
use App\Models\ProgramMentee;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MentoringMentorResource extends Resource
{
    protected static ?string $model = ProgramMentee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Mentoring';

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
            ->query(ProgramMentee::query()->with(['media', 'mentee', 'paketable', 'program.media'])->whereHas('program', function (Builder $query) {
                $query->where('program', 'Mentoring');
            })
            ->whereMentorId(auth()->user()->id)
            ->latest())
            ->columns([
                Tables\Columns\TextColumn::make('program.judul')
                    ->label('Program')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paket')
                    ->label('Paket')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->getStateUsing(fn(ProgramMentee $programMentee) => $programMentee->paketable->jenis . ' - ' . $programMentee->paketable->label),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->getStateUsing(fn(ProgramMentee $programMentee) => $programMentee->program->getFirstMediaUrl('program-thumbnail')),
                Tables\Columns\TextColumn::make('mentee.name')
                    ->label('Mentee')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->getStateUsing(fn(ProgramMentee $programMentee) => $programMentee->is_aktif ? 'Aktif' : 'Tidak Aktif')
                    ->color(fn(ProgramMentee $programMentee) => $programMentee->is_aktif ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('status_persetujuan')
                    ->label('Persetujuan Jadwal')
                    ->badge()
                    ->getStateUsing(function (ProgramMentee $record) {
                        // Jika tidak ada jadwal
                        // dd($record->getFirstMediaUrl('file-paket-lanjutan'));
                        if ($record->mentoringMenteeJadwal->isEmpty()) {
                            return 'Belum Ada Jadwal';
                        }
                
                        // Cek jika ada status Pending
                        if ($record->mentoringMenteeJadwal->contains('status', 'Menunggu')) {
                            return 'Menunggu';
                        }
                
                        // Cek jika ada status Rejected
                        if ($record->mentoringMenteeJadwal->contains('status', 'Ditolak')) {
                            return 'Ditolak';
                        }
                
                        // Jika semua Approved
                        if ($record->mentoringMenteeJadwal->every(function ($jadwal) {
                            return $jadwal->status === 'Disetujui';
                        })) {
                            return 'Disetujui';
                        }
                
                        return 'Status Tidak Valid';
                    })
                    ->color(function (ProgramMentee $record) {
                        if ($record->mentoringMenteeJadwal->isEmpty()) {
                            return 'gray';
                        }
                        
                        if ($record->mentoringMenteeJadwal->contains('status', 'Menunggu')) {
                            return 'warning';
                        }
                        
                        if ($record->mentoringMenteeJadwal->contains('status', 'Ditolak')) {
                            return 'danger';
                        }
                        
                        if ($record->mentoringMenteeJadwal->every(function ($jadwal) {
                            return $jadwal->status === 'Disetujui';
                        })) {
                            return 'success';
                        }
                        
                        return 'gray';
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('downloadFileLanjutan')
                    ->label('Download File Lanjutan')
                    ->icon('heroicon-o-eye')
                    ->url(fn(ProgramMentee $programMentee) => $programMentee->getFirstMediaUrl('file-paket-lanjutan'))
                    ->visible(fn(ProgramMentee $programMentee) => $programMentee->hasMedia('file-paket-lanjutan') && $programMentee->paketable->jenis === 'Lanjutan')
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('aturJadwal')
                    ->label('Atur Jadwal')
                    ->icon('heroicon-o-calendar-days')
                    ->url(fn(ProgramMentee $programMentee) => Pages\AturJadwalPage::getUrl(['record' => $programMentee->id]))
                    ->visible(fn(ProgramMentee $programMentee) => $programMentee->is_aktif),
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
            'index' => Pages\ManageMentoringMentors::route('/'),
            'jadwal' => Pages\AturJadwalPage::route('/{record}/jadwal'),
        ];
    }
}
