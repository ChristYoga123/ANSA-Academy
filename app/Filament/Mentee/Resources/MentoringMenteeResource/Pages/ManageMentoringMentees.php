<?php

namespace App\Filament\Mentee\Resources\MentoringMenteeResource\Pages;

use App\Filament\Mentee\Resources\MentoringMenteeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMentoringMentees extends ManageRecords
{
    protected static string $resource = MentoringMenteeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
