<?php

namespace App\Filament\Admin\Resources\ProofreadingMenteeResourcResource\Pages;

use App\Filament\Admin\Resources\ProofreadingMenteeResourcResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProofreadingMenteeResourc extends EditRecord
{
    protected static string $resource = ProofreadingMenteeResourcResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
