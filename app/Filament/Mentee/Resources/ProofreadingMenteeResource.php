<?php

namespace App\Filament\Mentee\Resources;

use CountWord;
use Exception;
use ZipArchive;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProgramMentee;
use Filament\Resources\Resource;
use App\Models\ProofreadingPaket;
use App\Models\ProofreadingMentee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ProofreadingMenteeSubmission;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Mentee\Resources\ProofreadingMenteeResource\Pages;
use App\Filament\Mentee\Resources\ProofreadingMenteeResource\RelationManagers;
use Illuminate\Support\Facades\Storage;

class ProofreadingMenteeResource extends Resource
{
    protected static ?string $model = ProgramMentee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Proofreading';

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
            ->query(ProgramMentee::query()->with(['paketable', 'program', 'proofreadingMenteeSubmission'])->whereMenteeId(auth()->id())->whereHas('program', function($query)
            {
                $query->whereProgram('Proofreading');
            })
            ->latest())
            ->columns([
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
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('lihatRawFile')
                    ->label('Lihat File Mentah')
                    ->icon('heroicon-o-eye')
                    ->url(fn(ProgramMentee $programMentee) => $programMentee->getFirstMediaUrl('proofreading-mentee-submission-raw')),
                Tables\Actions\Action::make('submitRawFile')
                    ->label('Kirim File Mentah')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('proofreading-mentee-submission-raw')
                            ->label('File Mentah (.doc, .docx, Max 10MB)')
                            ->required()
                            ->acceptedFileTypes(['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240)
                            ->maxFiles(1)
                            ->collection('proofreading-mentee-submission-raw'),
                    ])
                    ->action(function(ProgramMentee $programMentee)
                    {
                        DB::beginTransaction();
                        try {
                            $filePath = $programMentee->getFirstMedia('proofreading-mentee-submission-raw')->getPath();
                        
                            if (!file_exists($filePath)) {
                                throw new Exception('File tidak ditemukan');
                            }
                        
                            $zip = new ZipArchive();
                            if (!$zip->open($filePath)) {
                                $programMentee->clearMediaCollection('proofreading-mentee-submission-raw');
                                throw new Exception('Gagal membuka file');
                            }
                        
                            $content = $zip->getFromName('docProps/app.xml');
                            if (!$content || !preg_match('/<Pages>(\d+)<\/Pages>/', $content, $matches)) {
                                $zip->close();
                                $programMentee->clearMediaCollection('proofreading-mentee-submission-raw');
                                throw new Exception('Gagal membaca jumlah halaman');
                            }
                            $zip->close();
                            
                            $docCount = intval($matches[1]);
                            
                            // Cek halaman
                            $proofreadingPaket = ProofreadingPaket::find($programMentee->paketable_id);
                            if ($docCount < $proofreadingPaket->lembar_minimum || $docCount > $proofreadingPaket->lembar_maksimum) {
                                $programMentee->clearMediaCollection('proofreading-mentee-submission-raw');
                                throw new Exception('Jumlah lembar file tidak sesuai dengan ketentuan paket');
                            }
                        
                            // Create submission
                            ProofreadingMenteeSubmission::create([
                                'proofreading_mentee_id' => $programMentee->id,
                            ]);
                        
                            DB::commit();
                        
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Berhasil mengirim file mentah')
                                ->success()
                                ->send();
                        
                        } catch(Exception $e) {
                            DB::rollBack();
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                            Notification::make()
                                ->title('Error')
                                ->body('Gagal mengirim file mentah: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->hidden(fn(ProgramMentee $programMentee) => $programMentee->proofreadingMenteeSubmission),
                Tables\Actions\Action::make('lihatResultFile')
                    ->label('Lihat File Hasil Pemeriksaan')
                    ->icon('heroicon-o-eye')
                    ->url(fn(ProgramMentee $programMentee) => $programMentee->getFirstMediaUrl('proofreading-mentee-submission-result'))
                    ->visible(fn(ProgramMentee $programMentee) => $programMentee->proofreadingMenteeSubmission && $programMentee->proofreadingMenteeSubmission->is_selesai),
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
            'index' => Pages\ManageProofreadingMentees::route('/'),
        ];
    }
}
