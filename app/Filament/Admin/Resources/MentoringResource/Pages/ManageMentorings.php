<?php

namespace App\Filament\Admin\Resources\MentoringResource\Pages;

use App\Filament\Admin\Resources\MentoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageMentorings extends ManageRecords
{
    protected static string $resource = MentoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function(array $data)
                {
                    $data['judul'] = '[MENTORING] ' . $data['judul'];
                    $data['program'] = 'Mentoring';
                    return $data;
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Mentoring';
    }
}
