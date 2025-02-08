<?php

namespace App\Filament\Mentee\Resources\MentoringMenteeResource\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;
use App\Models\MentoringJadwal;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public $mentoringMenteeId;
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
            // 'hiddenDays' => [0],
            'allDaySlot' => false,
            'displayEventTime' => true,
            'displayEventEnd' => true,
            'businessHours' => [
                'daysOfWeek' => [1, 2, 3, 4, 5, 6, 7],
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
        // Get mentee's schedules
        $menteeSchedules = MentoringJadwal::query()->with('mentoringMentee.paketable.mentoring')
            // ->whereBetween('tanggal', [$info['start'], $info['end']])
            ->whereHas('mentoringMentee', function($query) {
                $query->where('assigned_by', auth()->id())
                    ->where('id', $this->mentoringMenteeId);
            })
            ->whereStatus('Disetujui')
            ->get()
            ->map(function(MentoringJadwal $jadwal) {
                $startDateTime = Carbon::parse($jadwal->waktu_mulai)->locale('id')->isoFormat('YYYY-MM-DD HH:mm:ss');
                $endDateTime = Carbon::parse($jadwal->waktu_selesai)->locale('id')->isoFormat('YYYY-MM-DD HH:mm:ss');

                return [
                    'id' => $jadwal->id,
                    'title' => "Pertemuan " . $jadwal->mentoringMentee->paketable->mentoring->judul,
                    'start' => $startDateTime,
                    'end' => $endDateTime,
                    'backgroundColor' => '#4CAF50',
                ];
            })
            ->toArray();

        // Get specific mentor's schedules
        $mentorSchedules = MentoringJadwal::query()
            ->whereHas('mentoringMentee', function($query) {
                $query->where('assigned_by', '!=', auth()->id())
                    ->where('assigned_by', function($subquery) {
                        $subquery->select('mentor_id')
                            ->from('program_mentees')
                            ->where('id', $this->mentoringMenteeId);
                    });
            })
            ->whereStatus('Disetujui')
            ->get()
            ->map(function(MentoringJadwal $jadwal) {
                $startDateTime = Carbon::parse($jadwal->waktu_mulai)->locale('id')->isoFormat('YYYY-MM-DD HH:mm:ss');
                $endDateTime = Carbon::parse($jadwal->waktu_selesai)->locale('id')->isoFormat('YYYY-MM-DD HH:mm:ss');

                return [
                    'id' => $jadwal->id,
                    'title' => 'Telah Dibooking',
                    'start' => $startDateTime,
                    'end' => $endDateTime,
                    'backgroundColor' => 'red'
                ];
            })
            ->toArray();

        return array_merge($menteeSchedules, $mentorSchedules);
    }

    protected function headerActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function modalActions(): array
    {
        return [
            // Actions\EditAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }


}
