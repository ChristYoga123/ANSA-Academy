<?php

namespace App\Filament\Mentee\Widgets;

use App\Models\Transaksi;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class ProfitChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Total Transaksi Menggunakan Referral Code';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Filter transactions for the authenticated mentor
        $data = Trend::query(
            Transaksi::query()->whereReferralCode(auth()->user()->referral_code)->whereStatus('Sukses')
        )
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Transaksi Mentee Menggunakan Referral Code (Sukses)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FFB1C1',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
