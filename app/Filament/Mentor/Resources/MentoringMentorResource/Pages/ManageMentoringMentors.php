<?php

namespace App\Filament\Mentor\Resources\MentoringMentorResource\Pages;

use App\Filament\Mentor\Resources\MentoringMentorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMentoringMentors extends ManageRecords
{
    protected static string $resource = MentoringMentorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
