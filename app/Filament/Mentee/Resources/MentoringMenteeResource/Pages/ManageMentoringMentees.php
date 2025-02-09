<?php

namespace App\Filament\Mentee\Resources\MentoringMenteeResource\Pages;

use App\Filament\Mentee\Resources\MentoringMenteeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageMentoringMentees extends ManageRecords
{
    protected static string $resource = MentoringMenteeResource::class;

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
