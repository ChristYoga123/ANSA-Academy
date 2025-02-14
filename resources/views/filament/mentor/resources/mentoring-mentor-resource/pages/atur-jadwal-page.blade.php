<x-filament-panels::page>
    <div class="flex justify-end">
        {{ $this->hubungiMenteeAction }}
    </div>
    {{ $this->table }}

    @livewire(\App\Filament\Mentor\Resources\MentoringMentorResource\Widgets\CalendarWidget::class)
</x-filament-panels::page>
