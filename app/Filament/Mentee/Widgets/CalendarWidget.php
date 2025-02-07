<?php

namespace App\Filament\Mentee\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;
use App\Models\MentoringJadwal;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
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
        // Get mentee's schedules
        $menteeSchedules = MentoringJadwal::query()->with('mentoringMentee.paketable.mentoring')
            ->whereAssignedBy(auth()->id())
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

        return array_merge($menteeSchedules);
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
