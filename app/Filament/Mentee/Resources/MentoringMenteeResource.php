<?php

namespace App\Filament\Mentee\Resources;

use App\Filament\Mentee\Resources\MentoringMenteeResource\Pages;
use App\Filament\Mentee\Resources\MentoringMenteeResource\RelationManagers;
use App\Models\MentoringMentee;
use App\Models\ProgramMentee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MentoringMenteeResource extends Resource
{
    protected static ?string $model = ProgramMentee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Mentoring';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $model): bool
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
            ->query(ProgramMentee::query()->with(['media', 'mentor', 'paketable'])->whereHas('program', function (Builder $query) {
                $query->where('program', 'Mentoring');
            })
            ->whereMenteeId(auth()->user()->id)
            ->latest())
            ->columns([
                Tables\Columns\TextColumn::make('program')
                    ->label('Program')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(fn(ProgramMentee $programMentee) => $programMentee->program->judul . ' - ' . $programMentee->paketable->label),
                Tables\Columns\TextColumn::make('mentor.name')
                    ->label('Mentor')
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
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('uploadFile')
                    ->label('Upload File')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('file')
                            ->label('File Khusus Paket Lanjutan (.pdf, Max 10MB)')
                            ->maxFiles(1)
                            ->maxSize(10240)
                            ->acceptedFileTypes(['application/pdf'])
                            ->rules('required')
                            ->collection('file-paket-lanjutan'),
                    ])
                    ->visible(fn(ProgramMentee $record) => $record->is_aktif && ($record->paketable->jenis == 'Lanjutan' && !$record->getFirstMedia('file-paket-lanjutan'))),
                Tables\Actions\Action::make('aturJadwal')
                    ->label('Atur Jadwal')
                    ->icon('heroicon-o-calendar-days')
                    ->url(fn(ProgramMentee $record) => Pages\AturJadwalPage::getUrl(['record' => $record->id]))
                    ->visible(function(ProgramMentee $record) {
                        if(!$record->is_aktif) {
                            return false;
                        }

                        if($record->paketable->jenis == 'Lanjutan') {
                            return $record->getFirstMedia('file-paket-lanjutan') ? true : false;
                        }

                        return true;
                    }),
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
            'index' => Pages\ManageMentoringMentees::route('/'),
            'jadwal' => Pages\AturJadwalPage::route('/{record}/jadwal'),
        ];
    }
}
