<?php

namespace App\Filament\Mentor\Resources\MenteeResource\Pages;

use App\Filament\Mentor\Resources\MenteeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMentees extends ManageRecords
{
    protected static string $resource = MenteeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
