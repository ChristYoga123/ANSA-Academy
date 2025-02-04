<?php

namespace App\Filament\Mentor\Resources\ProofreadingMentorResource\Pages;

use Filament\Actions;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Mentor\Resources\ProofreadingMentorResource;

class ManageProofreadingMentors extends ManageRecords
{
    protected static string $resource = ProofreadingMentorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Proofreading';
    }
}
