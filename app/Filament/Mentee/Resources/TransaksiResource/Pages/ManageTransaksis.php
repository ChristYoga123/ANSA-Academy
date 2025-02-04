<?php

namespace App\Filament\Mentee\Resources\TransaksiResource\Pages;

use App\Filament\Mentee\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageTransaksis extends ManageRecords
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Transaksi';
    }
}
