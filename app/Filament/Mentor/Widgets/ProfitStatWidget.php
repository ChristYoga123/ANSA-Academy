<?php

namespace App\Filament\Mentor\Widgets;

use App\Models\ProdukDigital;
use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProfitStatWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // $saldoMentor = Transaksi::query()->whereTransaksiableType(ProdukDigital::class)->whereHas('transaksiable', function($query)
        // {
        //     $query->where('mentor_id', auth()->id());
        // })
        // ->whereStatus('Sukses');

        // pembagian profit, mentor mendapatkan 60% per transaksi produk digital
        $transaksiProdigMentor = Transaksi::query()->whereTransaksiableType(ProdukDigital::
        class)->whereStatus('Sukses')->get();
        // cari transaksi produk digital yang mentor adalah user yang sedang login
        $transaksiProdigMentor = $transaksiProdigMentor->filter(function ($transaksi) {
            return $transaksi->transaksiable->mentor_id == auth()->id();
        });
        $saldoMentor = 0;
        foreach ($transaksiProdigMentor as $transaksi) {
            $saldoMentor += $transaksi->total_harga * 0.6;
        }

        return [
            Stat::make('Saldoku', 'Rp' . number_format($saldoMentor, 0, ',', '.')),
            Stat::make('Total Transaksi', $transaksiProdigMentor->count()),
        ];
    }
}
