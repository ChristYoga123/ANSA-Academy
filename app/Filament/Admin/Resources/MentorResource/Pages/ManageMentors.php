<?php

namespace App\Filament\Admin\Resources\MentorResource\Pages;

use App\Filament\Admin\Resources\MentorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageMentors extends ManageRecords
{
    protected static string $resource = MentorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Mentor';
    }
}
