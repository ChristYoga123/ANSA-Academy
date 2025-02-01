<?php

namespace App\Filament\Admin\Resources\KelasAnsaResource\Pages;

use App\Filament\Admin\Resources\KelasAnsaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageKelasAnsas extends ManageRecords
{
    protected static string $resource = KelasAnsaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->closeModalByClickingAway(false)
                ->mutateFormDataUsing(function (array $data)
                {
                    $data['program'] = 'Kelas ANSA';
                    return $data;
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Kelas ANSA';
    }
}
