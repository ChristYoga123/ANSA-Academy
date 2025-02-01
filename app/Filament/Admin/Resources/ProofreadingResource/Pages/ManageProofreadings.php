<?php

namespace App\Filament\Admin\Resources\ProofreadingResource\Pages;

use App\Filament\Admin\Resources\ProofreadingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageProofreadings extends ManageRecords
{
    protected static string $resource = ProofreadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->closeModalByClickingAway(false)
                ->mutateFormDataUsing(function(array $data)
                {
                    $data['program'] = 'Proofreading';

                    return $data;
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Proofreading';
    }
}
