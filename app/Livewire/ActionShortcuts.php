<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class ActionShortcuts extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;
    public function callAdmin(): Action
    {
        return Action::make('callAdmin')
            ->color('primary')
            ->label('Hubungi Admin')
            ->icon('heroicon-o-phone')
            ->keyBindings(['command+c', 'ctrl+c'])
            ->extraAttributes(['class' => 'w-full'])
            ->url(fn () => 'https://wa.me/6283191260587')
            ->openUrlInNewTab();
    }

    public function render()
    {
        return <<<'HTML'
            <div class="space-y-2">
                {{ $this->callAdmin }}
            </div>
        HTML;
    }
}
