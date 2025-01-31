<?php

namespace App\Filament\Admin\Resources\LokerMentorBidangResource\Pages;

use App\Filament\Admin\Resources\LokerMentorBidangResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageLokerMentorBidangs extends ManageRecords
{
    protected static string $resource = LokerMentorBidangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->closeModalByClickingAway(false),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Bidang Loker Mentor';
    }
}
