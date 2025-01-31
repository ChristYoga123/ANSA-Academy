<?php

namespace App\Filament\Admin\Resources\LokerMentorResource\Pages;

use App\Filament\Admin\Resources\LokerMentorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageLokerMentors extends ManageRecords
{
    protected static string $resource = LokerMentorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Calon Mentor';
    }
}
