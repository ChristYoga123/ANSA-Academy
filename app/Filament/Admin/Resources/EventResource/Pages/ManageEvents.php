<?php

namespace App\Filament\Admin\Resources\EventResource\Pages;

use App\Filament\Admin\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageEvents extends ManageRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->closeModalByClickingAway(false),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Event';
    }
}
