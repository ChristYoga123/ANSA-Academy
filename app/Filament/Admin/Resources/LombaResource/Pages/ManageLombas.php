<?php

namespace App\Filament\Admin\Resources\LombaResource\Pages;

use App\Filament\Admin\Resources\LombaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageLombas extends ManageRecords
{
    protected static string $resource = LombaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->closeModalByClickingAway(false)
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Lomba';
    }
}
