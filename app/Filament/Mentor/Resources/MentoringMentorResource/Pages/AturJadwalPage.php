<?php

namespace App\Filament\Mentor\Resources\MentoringMentorResource\Pages;

use Carbon\Carbon;
use Filament\Tables\Table;
use App\Models\ProgramMentee;
use App\Models\MentoringJadwal;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Infolists\Components\Section;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components\Fieldset;
use Filament\Actions\Action as ActionAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Mentor\Resources\MentoringMentorResource;
use Filament\Infolists\Components\Actions\Action as InfolistAction;

class AturJadwalPage extends Page implements HasTable, HasActions, HasForms
{
    use InteractsWithForms, InteractsWithForms, InteractsWithTable;

    protected static string $resource = MentoringMentorResource::class;

    protected static string $view = 'filament.mentor.resources.mentoring-mentor-resource.pages.atur-jadwal-page';

    public $jadwal;

    public function mount()
    {
        $mentoringMentee = ProgramMentee::with(['paketable', 'mentor', 'mentee'])->whereId(request()->route('record'))->whereMentorId(auth()->id())->first();
        if(!$mentoringMentee) {
            Notification::make()
                ->title('Error')
                ->body('Data tidak ditemukan')
                ->danger()
                ->send();

            return redirect()->route('filament.mentor.resources.mentoring-mentors.index');
        }

        $this->jadwal = $mentoringMentee;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Atur Jadwal Mentoring';
    }

    public function hubungiMenteeAction(): ActionAction
    {
        return ActionAction::make('hubungiMentee')
            ->label('Hubungi Mentee')
            ->icon('heroicon-o-phone')
            ->url(fn() => "https://wa.me/" . trim($this->jadwal?->mentee?->custom_fields['no_hp'], '+') ?? '-');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->jadwal ? MentoringJadwal::query()->with(['mentoringMentee', 'mentoringMentee.mentee', 'mentoringMentee.program.media'])->whereMentoringMenteeId($this->jadwal->id) : MentoringJadwal::query())
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
                                            ->prefixAction(
                                                InfolistAction::make('call')
                                                    ->label('Call Mentor')
                                                    ->icon('heroicon-o-phone')
                                                    // wa.me
                                                    ->url(fn ($record) => 'https://wa.me/' . trim($record->mentoringMentee->mentee->custom_fields['no_hp'], '+') ?? '6282230555413')
                                                    ->color(Color::Blue)
                                                    ->size(ActionSize::Medium)
                                            )
                                            ->columnSpan(1),
                                        TextEntry::make('mentoringMentee.mentor.name')
                                            ->label('Nama Mentor')
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
                Action::make('terimaJadwal')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn(MentoringJadwal $jadwal) => $jadwal->update(['status' => 'Disetujui']))
                    ->visible(fn(MentoringJadwal $jadwal) => $jadwal->status == 'Menunggu')
                    ->requiresConfirmation(),
                Action::make('tolakJadwal')
                    ->label('Tolak Jadwal')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn(MentoringJadwal $jadwal) => $jadwal->update(['status' => 'Ditolak']))
                    ->visible(fn(MentoringJadwal $jadwal) => $jadwal->status == 'Menunggu')
                    ->requiresConfirmation(),
                Action::make('selesaiJadwal')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function(MentoringJadwal $jadwal) {
                        $jadwal->update(['is_selesai' => true]);
                
                        $jumlahPertemuanSelesai = MentoringJadwal::whereMentoringMenteeId($jadwal->mentoring_mentee_id)->whereIsSelesai(true)->count();
                        $jumlahPertemuanDisepakati = $this->jadwal->paketable->jumlah_pertemuan;
                        
                        if($jumlahPertemuanSelesai == $jumlahPertemuanDisepakati) {
                            // matikan status mentoring mentee
                            $jadwal->mentoringMentee->update(['is_aktif' => false]);
                        } 
                    })
                    ->visible(fn(MentoringJadwal $jadwal) => $jadwal->status == 'Disetujui' && !$jadwal->is_selesai)
                    ->requiresConfirmation(),
                Action::make('linkMeeting')
                    ->label('Link Meeting')
                    ->icon('heroicon-o-link')
                    ->color('warning')
                    ->form([
                        TextInput::make('link_meet')
                            ->label('Link Meeting')
                            ->url()
                            ->placeholder('Masukkan link meeting')
                            ->required(),
                    ])
                    ->action(fn(array $data, MentoringJadwal $jadwal) => $jadwal->update($data))
                    ->visible(fn(MentoringJadwal $jadwal) => $jadwal->status == 'Disetujui' && empty($jadwal->link_meet)),
                Action::make('linkMeet')
                    ->label('Masuk Meet')
                    ->icon('heroicon-o-link')
                    ->url(fn(MentoringJadwal $jadwal) => $jadwal->link_meet)
                    ->visible(fn(MentoringJadwal $jadwal) => $jadwal->status == 'Disetujui' && !$jadwal->is_selesai)
                    ->openUrlInNewTab(),
            ])
            ;
    }
}
