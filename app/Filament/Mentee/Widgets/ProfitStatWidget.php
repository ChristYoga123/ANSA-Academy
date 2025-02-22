<?php

namespace App\Filament\Mentee\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProfitStatWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $transaksiMentee = Transaksi::where('status', 'Sukses')
            ->whereReferralCode(auth()->user()->referral_code)
            ->get();

        $saldoMentee = 0;
        foreach ($transaksiMentee as $transaksi) {
            $saldoMentee += $transaksi->total_harga * 0.15;
        }
        return [
            Stat::make('Saldoku', 'Rp' . number_format($saldoMentee, 0, ',', '.')),
            Stat::make('Total Transaksi', $transaksiMentee->count()),
        ];
    }
}
