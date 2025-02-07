<x-filament-panels::page>
    <div class="flex justify-end">
        {{ $this->createJadwalAction }}
    </div>
    {{ $this->table }}

    @livewire(\App\Filament\Mentee\Resources\MentoringMenteeResource\Widgets\CalendarWidget::class, [
        'mentoringMenteeId' => $this->jadwal->id,
    ])
</x-filament-panels::page>
