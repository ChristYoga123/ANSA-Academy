<?php

namespace App\Filament\Mentor\Widgets;

use App\Models\Transaksi;
use Flowframe\Trend\Trend;
use App\Models\ProdukDigital;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class ProfitChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Transaksi Produk Digital';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Filter transactions for the authenticated mentor
        $data = Trend::query(
            Transaksi::query()
                    ->where('status', 'Sukses')
                    ->where('transaksiable_type', ProdukDigital::class)
                    ->whereIn('transaksiable_id', function($query) {
                        $query->select('id')
                            ->from('produk_digitals')
                            ->where('mentor_id', auth()->id());
                    })
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
                    'label' => 'Transaksi Mentor',
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
