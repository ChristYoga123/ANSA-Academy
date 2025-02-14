<x-filament-panels::page>
    <div class="flex justify-end gap-3">
        {{ $this->hubungiMentorAction }}
        {{ $this->createJadwalAction }}
    </div>

    {{ $this->table }}

    @livewire(\App\Filament\Mentee\Resources\MentoringMenteeResource\Widgets\CalendarWidget::class, [
        'mentoringMenteeId' => $this->jadwal->id,
    ])
</x-filament-panels::page>
