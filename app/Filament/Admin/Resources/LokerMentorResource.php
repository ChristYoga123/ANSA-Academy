<?php

namespace App\Filament\Admin\Resources;

use Exception;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LokerMentor;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\LokerMentor\RejectionMail;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use App\Mail\LokerMentor\AcceptanceMail;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Mail\LokerMentor\LolosTahapanMail;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\LokerMentorResource\Pages;
use App\Filament\Admin\Resources\LokerMentorResource\RelationManagers;

class LokerMentorResource extends Resource
{
    protected static ?string $model = LokerMentor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Calon Mentor';

    public static function canCreate(): bool
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
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokerMentorBidang.nama')
                    ->label('Bidang yang dilamar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_penerimaan')
                    ->badge()
                    ->color(fn(LokerMentor $record) => match ($record->status_penerimaan) {
                        'Menunggu' => 'warning',
                        'Lolos Berkas' => 'info',
                        'Lolos Wawancara' => 'info',
                        'Lolos Microteaching' => 'info',
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
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
                Tables\Actions\Action::make('lihatCV')
                    ->icon('heroicon-o-document')
                    ->color('info')
                    ->label('CV')
                    ->url(fn(LokerMentor $lokerMentor) => $lokerMentor->drive_cv)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('Lolos Berkas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\RichEditor::make('catatan')
                            ->label('Catatan (bisa melampirkan file tambahan)')
                            ->required(),
                    ])
                    ->action(function (LokerMentor $lokerMentor, array $data)
                    {
                        DB::beginTransaction();
                        try
                        {
                            $lokerMentor->update(['status_penerimaan' => 'Lolos Berkas']);

                            Mail::to($lokerMentor->email)->queue(new LolosTahapanMail($lokerMentor, $lokerMentor->status_penerimaan, $data['catatan']));
                            
                            DB::commit();
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Calon mentor lolos berkas')
                                ->success()
                                ->send();
                        }catch(Exception $e)
                        {
                            DB::rollBack();
                            Notification::make()
                                ->title('Gagal')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn(LokerMentor $lokerMentor) => $lokerMentor->status_penerimaan === 'Menunggu'),
                Tables\Actions\Action::make('Lolos Wawancara')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\RichEditor::make('catatan')
                            ->label('Catatan (bisa melampirkan file tambahan)')
                            ->required(),
                    ])
                    ->action(function (LokerMentor $lokerMentor, array $data)
                    {
                        DB::beginTransaction();
                        try
                        {
                            $lokerMentor->update(['status_penerimaan' => 'Lolos Wawancara']);

                            Mail::to($lokerMentor->email)->queue(new LolosTahapanMail($lokerMentor, $lokerMentor->status_penerimaan, $data['catatan']));
                            
                            DB::commit();
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Calon mentor lolos wawancara')
                                ->success()
                                ->send();
                        }catch(Exception $e)
                        {
                            DB::rollBack();
                            Notification::make()
                                ->title('Gagal')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn(LokerMentor $lokerMentor) => $lokerMentor->status_penerimaan === 'Lolos Berkas'),
                Tables\Actions\Action::make('Lolos Microteaching')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\RichEditor::make('catatan')
                            ->label('Catatan (bisa melampirkan file tambahan)')
                            ->required(),
                        ])
                        ->action(function (LokerMentor $lokerMentor, array $data)
                        {
                            DB::beginTransaction();
                            try
                            {
                                $lokerMentor->update(['status_penerimaan' => 'Lolos Microteaching']);

                                Mail::to($lokerMentor->email)->queue(new LolosTahapanMail($lokerMentor, $lokerMentor->status_penerimaan, $data['catatan']));
                                
                                DB::commit();
                                Notification::make()
                                    ->title('Berhasil')
                                    ->body('Calon mentor lolos microteaching')
                                    ->success()
                                    ->send();
                            }catch(Exception $e)
                            {
                                DB::rollBack();
                                Notification::make()
                                    ->title('Gagal')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
                    ->visible(fn(LokerMentor $lokerMentor) => $lokerMentor->status_penerimaan === 'Lolos Wawancara'),
                Tables\Actions\Action::make('Diterima')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->label('Terima')
                    ->action(function(LokerMentor $lokerMentor){
                        DB::beginTransaction();
                        try
                        {
                            $lokerMentor->update(['status_penerimaan' => 'Diterima']);

                            $password = strtolower(Str::random(8));

                            // create user mentor
                            $user = User::firstOrCreate([
                                'name' => $lokerMentor->nama,
                                'email' => $lokerMentor->email,
                            ], [
                                'password' => $password,
                                'custom_fields' => [
                                    'no_hp' => $lokerMentor->no_hp,
                                    'universitas' => $lokerMentor->universitas,
                                    'semester' => $lokerMentor->semester,
                                    'alasan_mendaftar' => $lokerMentor->alasan_mendaftar,
                                    'mahasiswa_berprestrasi' => $lokerMentor->mahasiswa_berprestrasi,
                                    'pencapaian' => $lokerMentor->pencapaian,
                                    'drive_portofolio' => $lokerMentor->drive_portofolio,
                                    'drive_cv' => $lokerMentor->drive_cv,
                                    'linkedin' => $lokerMentor->linkedin,
                                    'instagram' => $lokerMentor->instagram,
                                    'bidang_mentor' => $lokerMentor->lokerMentorBidang->nama,
                                ]
                            ]);

                            $user->assignRole('mentor');

                            Mail::to($lokerMentor->email)->queue(new AcceptanceMail($user, $password));
                            
                            DB::commit();
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Calon mentor diterima')
                                ->success()
                                ->send();
                        }catch(Exception $e)
                        {
                            DB::rollBack();
                            Notification::make()
                                ->title('Gagal')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->label('Tolak')
                    ->visible(fn(LokerMentor $lokerMentor) => $lokerMentor->status_penerimaan === 'Menunggu')
                    ->form([
                        Textarea::make('alasan_ditolak')
                            ->required(),
                    ])
                    ->action(function(LokerMentor $lokerMentor, array $data){
                        DB::beginTransaction();
                        try
                        {
                            $lokerMentor->update(['status_penerimaan' => 'Ditolak']);
                            $lokerMentor->update(['alasan_ditolak' => $data['alasan_ditolak']]);

                            Mail::to($lokerMentor->email)->queue(new RejectionMail($lokerMentor));
                            
                            DB::commit();
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Data loker mentor ditolak')
                                ->success()
                                ->send();
                        }catch(Exception $e)
                        {
                            DB::rollBack();
                            Notification::make()
                                ->title('Gagal')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\ViewAction::make()
                    ->infolist([
                        Fieldset::make('Data Diri')
                            ->schema([
                                TextEntry::make('nama'),
                                TextEntry::make('email')
                                    ->prefixAction(
                                        Action::make('email')
                                            ->icon('heroicon-o-envelope')
                                            ->url(fn(LokerMentor $record) => "mailto:{$record->email}")
                                            ->openUrlInNewTab()
                                    ),
                                TextEntry::make('no_hp')
                                    ->prefixAction(
                                        Action::make('phone')
                                            ->icon('heroicon-o-phone')
                                            ->url(fn(LokerMentor $record) => "https://wa.me/{$record->no_hp}")
                                            ->openUrlInNewTab()
                                    ),
                                TextEntry::make('universitas'),
                                TextEntry::make('semester'),
                                TextEntry::make('lokerMentorBidang.nama')
                                    ->label('Bidang yang dilamar')
                                    ->columnSpanFull(),
                            ]),
                        
                        Fieldset::make('Prestasi & Portofolio')
                            ->schema([
                                TextEntry::make('mahasiswa_berprestrasi'),
                                TextEntry::make('alasan_mendaftar'),
                                TextEntry::make('drive_portofolio')
                                    ->columnSpanFull()
                                    ->prefixAction(
                                    Action::make('drive')
                                    ->icon('heroicon-o-link')
                                    ->url(fn(LokerMentor $record) => $record->drive_portofolio)
                                    ->openUrlInNewTab()
                                ),
                                TextEntry::make('pencapaian')
                                    ->columnSpanFull()
                                    ->formatStateUsing(function(LokerMentor $record){
                                        $result = [];
                                        foreach($record->pencapaian as $key => $value) {
                                            $result[] = ($key + 1) . ' : ' . $value;
                                        }
                                        return nl2br(implode("\n", $result));
                                        // atau bisa langsung: return implode("<br>", $result);
                                    })
                                    ->html(),  // Penting! Tambahkan ini agar HTML tag dirender
                            ]),
                        
                        Fieldset::make('Media Sosial')
                            ->schema([
                                TextEntry::make('linkedin')
                                    ->prefixAction(
                                        Action::make('linkedin')
                                            ->icon('heroicon-o-link')
                                            ->url(fn(LokerMentor $record) => $record->linkedin)
                                            ->openUrlInNewTab()
                                    ),
                                TextEntry::make('instagram')
                                    ->prefixAction(
                                        Action::make('instagram')
                                            ->icon('heroicon-o-link')
                                            ->url(fn(LokerMentor $record) => $record->instagram)
                                            ->openUrlInNewTab()
                                    ),
                            ]),
                        
                        Fieldset::make('Status')
                            ->schema([
                                Grid::make()
                                    ->columns(1)
                                    ->schema([
                                        TextEntry::make('status_penerimaan')
                                            ->badge()
                                            ->color(fn(LokerMentor $record) => match ($record->status_penerimaan) {
                                                'Diterima' => 'success',
                                                'Ditolak' => 'danger',
                                                'Menunggu' => 'warning',
                                                default => 'info',
                                            }),
                                        TextEntry::make('alasan_ditolak')
                                            ->getStateUsing(fn(LokerMentor $record) => $record->alasan_ditolak ? $record->alasan_ditolak : 'Belum ada balasan')
                                            ->badge(fn(LokerMentor $record) => !$record->alasan_ditolak)
                                            ->color(fn(LokerMentor $record) => !$record->alasan_ditolak ? 'gray' : ''),
                                    ])
                            ]),
                    ]),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(LokerMentor $lokerMentor) => $lokerMentor->status_penerimaan !== 'Menunggu'),
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
            'index' => Pages\ManageLokerMentors::route('/'),
        ];
    }
}
