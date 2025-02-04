<?php

namespace App\Filament\Mentor\Resources\KelasAnsaMentorResource\Pages;

use Filament\Actions;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Mentor\Resources\KelasAnsaMentorResource;

class ManageKelasAnsaMentors extends ManageRecords
{
    protected static string $resource = KelasAnsaMentorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Kelas ANSA';
    }
}
