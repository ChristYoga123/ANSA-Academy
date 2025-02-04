<?php

namespace App\Filament\Mentee\Resources\KelasAnsaMenteeResource\Pages;

use App\Filament\Mentee\Resources\KelasAnsaMenteeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKelasAnsaMentees extends ManageRecords
{
    protected static string $resource = KelasAnsaMenteeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Kelas ANSA';
    }
}
