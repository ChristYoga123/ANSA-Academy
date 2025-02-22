<?php

namespace App\Filament\Mentor\Widgets;

use Filament\Tables;
use App\Models\Transaksi;
use Filament\Tables\Table;
use App\Models\ProdukDigital;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class ProfitTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Transaksi Produk Digital';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaksi::query()
                    ->where('status', 'Sukses')
                    ->where('transaksiable_type', ProdukDigital::class)
                    ->whereIn('transaksiable_id', function($query) {
                        $query->select('id')
                            ->from('produk_digitals')
                            ->where('mentor_id', auth()->id());
                    })
            )
            ->columns([
                TextColumn::make('order_id')
                    ->searchable()
                    ->weight(FontWeight::Bold),
                TextColumn::make('mentee.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('program')
                    ->label('Produk Digital')
                    ->getStateUsing(fn(Transaksi $transaksi) => match($transaksi->transaksiable_type) {
                        ProdukDigital::class => $transaksi->transaksiable->judul,
                    }),
                TextColumn::make('referral_code')
                    ->searchable()
                    ->getStateUsing(fn(Transaksi $transaksi) => $transaksi->referral_code ?? 'N/A'),
                TextColumn::make('total_harga')
                    ->numeric()
                    ->money('IDR')
                    ->weight(FontWeight::Bold)
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'Menunggu' => 'warning',
                        'Sukses' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
