<?php

namespace App\Filament\Admin\Resources\ProgramKategoriResource\Pages;

use App\Filament\Admin\Resources\ProgramKategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageProgramKategoris extends ManageRecords
{
    protected static string $resource = ProgramKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Kategori Program';
    }
}
