<?php

namespace App\Filament\Admin\Resources\ProofreadingMenteeResourcResource\Pages;

use App\Filament\Admin\Resources\ProofreadingMenteeResourcResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProofreadingMenteeResourcs extends ListRecords
{
    protected static string $resource = ProofreadingMenteeResourcResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
