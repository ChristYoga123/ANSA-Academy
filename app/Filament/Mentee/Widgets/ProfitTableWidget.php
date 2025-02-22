<?php

namespace App\Filament\Mentee\Widgets;

use Filament\Tables;
use App\Models\Event;
use App\Models\Transaksi;
use Filament\Tables\Table;
use App\Models\ProdukDigital;
use App\Models\ProgramMentee;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class ProfitTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Transaksi Menggunakan Referral Code';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaksi::query()
                    ->with('transaksiable')
                    ->where('status', 'Sukses')
                    ->whereReferralCode(auth()->user()->referral_code)
            )
            ->columns([
                TextColumn::make('order_id')
                    ->searchable()
                    ->weight(FontWeight::Bold),
                TextColumn::make('program')
                    ->label('Program')
                    ->getStateUsing(fn(Transaksi $transaksi) => match($transaksi->transaksiable_type) {
                        ProdukDigital::class => $transaksi->transaksiable->judul,
                        Event::class => $transaksi->transaksiable->judul,
                        ProgramMentee::class => $transaksi->transaksiable->program->judul,
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
