<?php

namespace App\Filament\Mentee\Resources\MentoringMenteeResource\Pages;

use Carbon\Carbon;
use Filament\Tables\Table;
use App\Models\ProgramMentee;
use App\Models\MentoringJadwal;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Actions\Contracts\HasActions;
use Filament\Infolists\Components\Section;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use App\Filament\Mentee\Resources\MentoringMenteeResource;
use Filament\Infolists\Components\Actions\Action as InfolistAction;

class AturJadwalPage extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithForms, InteractsWithTable, InteractsWithActions;

    protected static string $resource = MentoringMenteeResource::class;

    protected static string $view = 'filament.mentee.resources.mentoring-mentee-resource.pages.atur-jadwal-page';

    public $jadwal;

    public function mount()
    {
        $mentoringMentee = ProgramMentee::with(['paketable', 'mentor', 'mentee'])->whereId(request()->route('record'))->whereMenteeId(auth()->id())->whereIsAktif(true)->first();
        if(!$mentoringMentee) {
            Notification::make()
                ->title('Error')
                ->body('Data tidak ditemukan')
                ->danger()
                ->send();

            return redirect()->route('filament.mentee.resources.mentoring-mentees.index');
        }

        $this->jadwal = $mentoringMentee;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Atur Jadwal';
    }

    public function createJadwalAction(): CreateAction
    {
        return CreateAction::make('createJadwal')
            ->label('Atur Jadwal')
            ->model(MentoringJadwal::class)
            ->form([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        // DateTimePicker::make('waktu')
                        //     ->required(),
                        DatePicker::make('tanggal')
                            ->required()
                            ->locale('id'),
                        Select::make('waktu')
                            ->required()
                            ->label('Sesi Pertemuan')
                            ->options([
                                /**
                                 * Sesi 1: 07.00-08.00
                                    Sesi 2 : 08.30-09.30
                                    Sesi 3 : 10.00-11.00
                                    Sesi 4 : 12.00-1300
                                    Sesi 5 : 13.30-14.30
                                    Sesi 6 : 15.30-16.30
                                    Sesi 7 : 19.00-20.00
                                    Sesi 8 : 20.15-21.15
                                 */
                                
                                '07:00:00' => 'Sesi 1: 07.00-08.00',
                                '08:30:00' => 'Sesi 2: 08.30-09.30',
                                '10:00:00' => 'Sesi 3: 10.00-11.00',
                                '12:00:00' => 'Sesi 4: 12.00-13.00',
                                '13:30:00' => 'Sesi 5: 13.30-14.30',
                                '15:30:00' => 'Sesi 6: 15.30-16.30',
                                '19:00:00' => 'Sesi 7: 19.00-20.00',
                                '20:15:00' => 'Sesi 8: 20.15-21.15',
                            ])
                    ]),
            ])
            ->using(function(array $data) {
                // jika jadwal yang dibuat melebihi jumlah pertemuan
                if(MentoringJadwal::whereAssignedBy(auth()->id())->whereMentoringMenteeId($this->jadwal->id)->count() >= $this->jadwal->paketable->jumlah_pertemuan) {
                    Notification::make()
                        ->title('Error')
                        ->body('Jumlah pertemuan sudah mencapai batas')
                        ->danger()
                        ->send();

                    return;
                }

                // Jika jadwal yang diajukan bertabrakan dengan jadwal yang sudah ada
                $waktu_mulai = Carbon::parse($data['tanggal'] . ' ' . $data['waktu']);
                $waktu_selesai = Carbon::parse($data['tanggal'] . ' ' . $data['waktu'])->addMinutes(60);

                $jadwal_sudah_ada = MentoringJadwal::where(function($query) use ($waktu_mulai, $waktu_selesai) {
                    $query->whereBetween('waktu_mulai', [$waktu_mulai, $waktu_selesai])
                        ->orWhereBetween('waktu_selesai', [$waktu_mulai, $waktu_selesai]);
                })
                ->where('id', '!=', $this->jadwal->id)
                ->whereStatus('Disetujui')
                ->exists();
                
                if($jadwal_sudah_ada) {
                    Notification::make()
                        ->title('Error')
                        ->body('Jadwal yang diajukan bertabrakan dengan jadwal yang sudah ada')
                        ->danger()
                        ->send();
                    return;
                }

                MentoringJadwal::create([
                    'mentoring_mentee_id' => $this->jadwal->id,
                    'assigned_by' => auth()->id(),
                    'jadwal' => $this->jadwal->mentee->name . ' - ' . $this->jadwal->program->judul . ' - ' . $this->jadwal->paketable->label,
                    'waktu_mulai' => $waktu_mulai,
                    'waktu_selesai' => $waktu_selesai,
                ]);

                Notification::make()
                    ->title('Success')
                    ->body('Jadwal berhasil dibuat')
                    ->success()
                    ->send();
            })
            ->hidden(
                // terlihat jika jumlah jadwal yang sudah dibuat belum mencapai jumlah pertemuan
                fn() => MentoringJadwal::whereAssignedBy(auth()->id())->whereMentoringMenteeId($this->jadwal->id)->count() === $this->jadwal->paketable->jumlah_pertemuan
            )
            ->successNotification(null)
            ->createAnother(false);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn() => $this->jadwal ? MentoringJadwal::query()->with(['mentoringMentee', 'mentoringMentee.mentor', 'mentoringMentee.program.media'])->whereMentoringMenteeId($this->jadwal->id)->whereAssignedBy(auth()->id()) : MentoringJadwal::query())
            ->columns([
                TextColumn::make('mentor')
                    ->label('Mentor')
                    ->getStateUsing(fn(MentoringJadwal $jadwal) => $jadwal->mentoringMentee->mentor->name),
                TextColumn::make('jadwal')
                    ->searchable(),
                TextColumn::make('waktu')
                    ->sortable()
                    ->getStateUsing(fn(MentoringJadwal $jadwal) => Carbon::parse($jadwal->waktu_mulai)->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') . '-' . Carbon::parse($jadwal->waktu_selesai)->locale('id')->isoFormat('HH:mm')),
                TextColumn::make('status')
                    ->badge()
                    ->label('Persetujuan')
                    ->getStateUsing(fn(MentoringJadwal $jadwal) => match($jadwal->status) {
                        'Menunggu' => 'Menunggu Persetujuan',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                    })
                    ->color(fn(MentoringJadwal $jadwal) => match($jadwal->status) {
                        'Menunggu' => 'warning',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                    }),
                TextColumn::make('is_selesai')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn(MentoringJadwal $jadwal) => $jadwal->is_selesai ? 'Selesai' : 'Belum Selesai')
                    ->color(fn(MentoringJadwal $jadwal) => $jadwal->is_selesai ? 'success' : 'warning'),
            ])
            ->actions([
                Action::make('detailJadwal')
                    ->label('Detail Jadwal')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->infolist([
                        Section::make()
                            ->schema([
                                Fieldset::make('dataPelaksana')
                                    ->label('Pelaksanaan')
                                    ->schema([
                                        TextEntry::make('mentoringMentee.program.judul')
                                            ->label('Judul Mentoring')
                                            ->columnSpan(2),
                                        TextEntry::make('mentoringMentee.mentee.name')
                                            ->label('Nama Mentee')
                                            ->columnSpan(1),
                                        TextEntry::make('mentoringMentee.mentor.name')
                                            ->label('Nama Mentor')
                                            ->prefixAction(
                                                InfolistAction::make('call')
                                                    ->label('Call Mentor')
                                                    ->icon('heroicon-o-phone')
                                                    // wa.me
                                                    ->url(fn ($record) => 'https://wa.me/' . $record->mentoringMentee->mentor->custom_fields['no_hp'] ?? '6282230555413')
                                                    ->color(Color::Blue)
                                                    ->size(ActionSize::Medium)
                                            )
                                            ->columnSpan(1),
                                    ]),
                                Fieldset::make('Waktu & Pertemuan')
                                    ->label('Waktu & Pertemuan')
                                    ->schema([
                                        TextEntry::make('hari')
                                            ->label('Tanggal')
                                            ->iconColor(Color::Green)
                                            ->getStateUsing(fn(MentoringJadwal $jadwal) => Carbon::parse($jadwal->waktu)->locale('id')->isoFormat('dddd, D MMMM Y'))
                                            ->icon('heroicon-o-calendar')
                                            ->columnSpan(1),
                                        TextEntry::make('waktu')
                                            ->label('Waktu')
                                            ->icon('heroicon-o-clock')
                                            ->iconColor(Color::Red)
                                            ->getStateUsing(fn(MentoringJadwal $jadwal) => Carbon::parse($jadwal->waktu_mulai)->locale('id')->isoFormat('HH:mm') . '-' . Carbon::parse($jadwal->waktu_selesai)->locale('id')->isoFormat('HH:mm'))
                                            ->columnSpan(1),
                                        TextEntry::make('link_meet')
                                            ->label('Link Meeting')
                                            ->getStateUsing(fn(MentoringJadwal $jadwal) => $jadwal->link_meet ?? 'Belum ada link meeting')
                                            ->columnSpan(2)
                                            ->prefixAction(
                                                InfolistAction::make('join_meeting')
                                                    ->label('Join Meeting')
                                                    ->icon('heroicon-o-video-camera')
                                                    ->url(fn ($record) => $record->link_meet, true)
                                                    ->color(Color::Blue)
                                                    ->size(ActionSize::Medium)
                                                    ->visible(fn ($record) => !empty($record->link_meet))
                                            ),
                                    ]),
                                Fieldset::make('Status')
                                    ->label('Status')
                                    ->schema([
                                        TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'Menunggu' => 'warning',
                                                'Disetujui' => 'success',
                                                'Ditolak' => 'danger',
                                            })
                                            ->columnSpan(1),

                                        TextEntry::make('is_selesai')
                                            ->label('Status Selesai')
                                            ->badge()
                                            ->color(fn (bool $state): string => $state ? 'success' : 'warning')
                                            ->formatStateUsing(fn (bool $state): string => $state ? 'Selesai' : 'Belum Selesai')
                                            ->columnSpan(1),
                                    ]),
                            ])
                            ->columns(2)
                    ])
                    ->visible(fn(MentoringJadwal $jadwal) => $jadwal->status != 'Ditolak')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
                EditAction::make('editJadwal')
                    ->label('Edit Jadwal')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Grid::make()
                            ->columns(1)
                            ->schema([
                                DatePicker::make('tanggal')
                                    ->required()
                                    ->locale('id')
                                    ->formatStateUsing(fn($record) => $record->waktu_mulai->format('Y-m-d')),
                                Select::make('waktu_mulai')
                                    ->required()
                                    ->options([
                                        '07:00:00' => 'Sesi 1: 07.00-08.00',
                                        '08:30:00' => 'Sesi 2: 08.30-09.30',
                                        '10:00:00' => 'Sesi 3: 10.00-11.00',
                                        '12:00:00' => 'Sesi 4: 12.00-13.00',
                                        '13:30:00' => 'Sesi 5: 13.30-14.30',
                                        '15:30:00' => 'Sesi 6: 15.30-16.30',
                                        '19:00:00' => 'Sesi 7: 19.00-20.00',
                                        '20:15:00' => 'Sesi 8: 20.15-21.15',
                                    ])
                                    ->formatStateUsing(fn($record) => $record->waktu_mulai->format('H:i:s')),
                            ]),
                    ])
                    ->using(function(array $data, MentoringJadwal $jadwal)
                    {
                        // Jika jadwal yang diajukan bertabrakan dengan jadwal yang sudah ada
                        $waktu_mulai = Carbon::parse($data['waktu_mulai']);
                        $waktu_selesai = Carbon::parse($data['waktu_mulai'])->addMinutes(60);

                        $jadwal_sudah_ada = MentoringJadwal::where(function($query) use ($waktu_mulai, $waktu_selesai) {
                            $query->whereBetween('waktu_mulai', [$waktu_mulai, $waktu_selesai])
                                ->orWhereBetween('waktu_selesai', [$waktu_mulai, $waktu_selesai]);
                        })

                        ->where('id', '!=', $jadwal->id)
                        ->whereStatus('Disetujui')
                        ->exists();

                        if($jadwal_sudah_ada) {
                            Notification::make()
                                ->title('Error')
                                ->body('Jadwal yang diajukan bertabrakan dengan jadwal yang sudah ada')
                                ->danger()
                                ->send();
                            return;
                        }

                        $jadwal->update([
                            'tanggal' => $data['tanggal'],
                            'waktu_mulai' => Carbon::parse($data['waktu_mulai']),
                            'waktu_selesai' => Carbon::parse($data['waktu_mulai'])->addMinutes(60),
                        ]);

                        Notification::make()
                            ->title('Success')
                            ->body('Jadwal berhasil diubah')
                            ->success()
                            ->send();
                    })
                    ->successNotification(null)
                    ->visible(fn(MentoringJadwal $jadwal) => ($jadwal->status == 'Menunggu' || $jadwal->status == 'Ditolak') && !$jadwal->is_selesai),
                Action::make('linkMeet')
                    ->label('Masuk Meet')
                    ->icon('heroicon-o-link')
                    ->url(fn(MentoringJadwal $jadwal) => $jadwal->link_meet)
                    ->visible(fn(MentoringJadwal $jadwal) => $jadwal->status == 'Disetujui' && !$jadwal->is_selesai)
                    ->openUrlInNewTab(),
                
            ]);
    }
}
