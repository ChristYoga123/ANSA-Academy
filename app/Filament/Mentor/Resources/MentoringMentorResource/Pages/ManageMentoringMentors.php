<?php

namespace App\Filament\Mentor\Resources\MentoringMentorResource\Pages;

use App\Filament\Mentor\Resources\MentoringMentorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageMentoringMentors extends ManageRecords
{
    protected static string $resource = MentoringMentorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Mentoring';
    }
}
