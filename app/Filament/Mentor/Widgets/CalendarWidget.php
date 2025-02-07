<?php

namespace App\Filament\Mentor\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;
use App\Models\MentoringJadwal;
use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DateTimePicker;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = MentoringJadwal::class;
    public function config(): array
    {
        return [
            'initialView' => 'timeGridWeek',
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'timeGridWeek'
            ],
            'slotMinTime' => '07:00:00',
            'slotMaxTime' => '23:00:00',
            'slotDuration' => '01:00:00',
            'hiddenDays' => [0],
            'allDaySlot' => false,
            'displayEventTime' => true,
            'displayEventEnd' => true,
            'businessHours' => [
                'daysOfWeek' => [1, 2, 3, 4, 5, 6],
                'startTime' => '07:00',
                'endTime' => '17:00',
            ],
            'slotLabelFormat' => [
                'hour' => '2-digit',
                'minute' => '2-digit',
                'hour12' => false
            ]
        ];
    }

    public function fetchEvents(array $info): array
    {
        return MentoringJadwal::query()
            ->with('mentoringMentee.paketable.mentoring')
            ->where(function($query) {
                $query->whereHas('mentoringMentee', function($q) {
                    $q->where('mentor_id', auth()->id());
                })
                ->orWhere('assigned_by', auth()->id());
            })
            ->whereStatus('Disetujui')
            ->get()
            ->map(function(MentoringJadwal $jadwal) {
                $startDateTime = Carbon::parse($jadwal->waktu_mulai)->locale('id')->isoFormat('YYYY-MM-DD HH:mm:ss');
                $endDateTime = Carbon::parse($jadwal->waktu_selesai)->locale('id')->isoFormat('YYYY-MM-DD HH:mm:ss');

                return [
                    'id' => $jadwal->id,
                    'title' => $jadwal?->mentoringMentee?->paketable?->mentoring?->judul ? 'Pertemuan ' . $jadwal?->mentoringMentee?->paketable?->mentoring?->judul :  $jadwal->jadwal,
                    'start' => $startDateTime,
                    'end' => $endDateTime,
                    'backgroundColor' => $jadwal->assigned_by == auth()->id() ? '#4CAF50' : 'red'
                ];
            })
            ->toArray();
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->model(MentoringJadwal::class)
                ->form([
                    TextInput::make('jadwal')
                        ->required(),
                    DateTimePicker::make('waktu_mulai')
                        ->required(),
                    DateTimePicker::make('waktu_selesai')
                        ->required(),
                ])
                ->action(function(array $data)
                {
                    // jika waktu sudah ada yang terisi
                    // Jika jadwal yang diajukan bertabrakan dengan jadwal yang sudah ada
                    $waktu_mulai = Carbon::parse($data['waktu_mulai']);
                    $waktu_selesai = Carbon::parse($data['waktu_selesai']);

                    $jadwal_sudah_ada = MentoringJadwal::where(function($query) use ($waktu_mulai, $waktu_selesai) {
                        $query->whereBetween('waktu_mulai', [$waktu_mulai, $waktu_selesai])
                            ->orWhereBetween('waktu_selesai', [$waktu_mulai, $waktu_selesai])
                            ->orWhere(function($q) use ($waktu_mulai, $waktu_selesai) {
                                $q->where('waktu_mulai', '<=', $waktu_mulai)
                                ->where('waktu_selesai', '>=', $waktu_selesai);
                            });
                    })
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
                        'jadwal' => $data['jadwal'],
                        'waktu_mulai' => $data['waktu_mulai'],
                        'waktu_selesai' => $data['waktu_selesai'],
                        'status' => 'Disetujui',
                        'assigned_by' => auth()->id()
                    ]);

                    Notification::make()
                        ->title('Success')
                        ->body('Jadwal berhasil ditambahkan')
                        ->success()
                        ->send();
                })
        ];
    }

}
