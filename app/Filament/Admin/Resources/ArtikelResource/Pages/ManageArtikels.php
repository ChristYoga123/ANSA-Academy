<?php

namespace App\Filament\Admin\Resources\ArtikelResource\Pages;

use App\Filament\Admin\Resources\ArtikelResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageArtikels extends ManageRecords
{
    protected static string $resource = ArtikelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->closeModalByClickingAway(false),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Artikel';
    }
}
