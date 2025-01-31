<?php

namespace App\Filament\Admin\Resources\MenteeResource\Pages;

use App\Filament\Admin\Resources\MenteeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageMentees extends ManageRecords
{
    protected static string $resource = MenteeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Mentee';
    }
}
