<?php

namespace App\Filament\Mentor\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProgramMentee;
use Filament\Resources\Resource;
use App\Models\ProofreadingMentor;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Mentor\Resources\ProofreadingMentorResource\Pages;
use App\Filament\Mentor\Resources\ProofreadingMentorResource\RelationManagers;

class ProofreadingMentorResource extends Resource
{
    protected static ?string $model = ProgramMentee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Proofreading';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $model): bool
    {
        return false;
    }

    public static function canEdit(Model $model): bool
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
            ->query(ProgramMentee::query()->with(['paketable', 'program', 'proofreadingMenteeSubmission'])->whereHas('program', function($query)
            {
                $query->whereProgram('Proofreading');
            })
            ->latest())
            ->columns([
                Tables\Columns\TextColumn::make('mentee.name')
                    ->label('Nama Mentee')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_program')
                    ->label('Program')
                    ->weight(FontWeight::SemiBold)
                    ->getStateUsing(fn(ProgramMentee $programMentee) => $programMentee->program->judul . ' - ' . $programMentee->paketable->label),
                Tables\Columns\TextColumn::make('is_aktif')
                    ->label('Status')
                    ->badge()
                    ->color(fn(ProgramMentee $programMentee) => $programMentee->is_aktif ? 'success' : 'danger')
                    ->getStateUsing(fn(ProgramMentee $programMentee) => $programMentee->is_aktif ? 'Aktif' : 'Tidak Aktif'),
                Tables\Columns\TextColumn::make('status_pemeriksaan')
                    ->label('Status Pemeriksaan')
                    ->badge()
                    ->color(function(ProgramMentee $proofreadingMentee)
                    {
                        if(!$proofreadingMentee->proofreadingMenteeSubmission)
                        {
                            return 'danger';
                        }
                        else
                        {
                            return $proofreadingMentee->proofreadingMenteeSubmission->is_selesai ? 'success' : 'warning';
                        }
                    })
                    ->getStateUsing(function(ProgramMentee $proofreadingMentee)
                    {
                        if(!$proofreadingMentee->proofreadingMenteeSubmission)
                        {
                            return 'Belum Mengirim';
                        }
                        else
                        {
                            return $proofreadingMentee->proofreadingMenteeSubmission->is_selesai ? 'Selesai Diperiksa' : 'Proses Pemeriksaan';
                        }
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
                Tables\Actions\Action::make('lihatRawFile')
                    ->label('Lihat File Mentah')
                    ->icon('heroicon-o-eye')
                    ->url(fn(ProgramMentee $programMentee) => $programMentee->getFirstMediaUrl('proofreading-mentee-submission-raw')),
                Tables\Actions\Action::make('submitResultFile')
                    ->label('Kirim Hasil Pemeriksaan')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('proofreading-mentee-submission-result')
                            ->label('File Hasil Pemeriksaan')
                            ->required()
                            ->acceptedFileTypes(['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240)
                            ->maxFiles(1)
                            ->collection('proofreading-mentee-submission-result'),
                    ])
                    ->action(function(ProgramMentee $programMentee)
                    {
                        $programMentee->proofreadingMenteeSubmission->update([
                            'is_selesai' => true,
                        ]);

                        $programMentee->update([
                            'is_aktif' => false,
                        ]);

                        Notification::make()
                            ->title('Sukses')
                            ->body('Hasil pemeriksaan telah berhasil dikirim')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(ProgramMentee $programMentee) => $programMentee->proofreadingMenteeSubmission && !$programMentee->proofreadingMenteeSubmission->is_selesai),
                Tables\Actions\Action::make('lihatResultFile')
                    ->label('Lihat File Hasil Pemeriksaan')
                    ->icon('heroicon-o-eye')
                    ->url(fn(ProgramMentee $programMentee) => $programMentee->getFirstMediaUrl('proofreading-mentee-submission-result'))
                    ->visible(fn(ProgramMentee $programMentee) => $programMentee->proofreadingMenteeSubmission && $programMentee->proofreadingMenteeSubmission->is_selesai),
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
            'index' => Pages\ManageProofreadingMentors::route('/'),
        ];
    }
}
