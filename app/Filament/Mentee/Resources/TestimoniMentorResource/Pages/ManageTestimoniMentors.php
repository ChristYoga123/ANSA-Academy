<?php

namespace App\Filament\Mentee\Resources\TestimoniMentorResource\Pages;

use App\Filament\Mentee\Resources\TestimoniMentorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageTestimoniMentors extends ManageRecords
{
    protected static string $resource = TestimoniMentorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Testimoni Mentor';
    }
}
