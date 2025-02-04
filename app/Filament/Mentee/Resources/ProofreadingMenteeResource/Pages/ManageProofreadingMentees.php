<?php

namespace App\Filament\Mentee\Resources\ProofreadingMenteeResource\Pages;

use App\Filament\Mentee\Resources\ProofreadingMenteeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageProofreadingMentees extends ManageRecords
{
    protected static string $resource = ProofreadingMenteeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Proofreading'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Proofreading';
    }
}
