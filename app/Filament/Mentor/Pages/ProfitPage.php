<?php

namespace App\Filament\Mentor\Pages;

use Filament\Pages\Page;
use App\Models\Transaksi;
use App\Models\ProdukDigital;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Mentor\Widgets\ProfitStatWidget;
use App\Filament\Mentor\Widgets\ProfitChartWidget;
use App\Filament\Mentor\Widgets\ProfitTableWidget;

class ProfitPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string $view = 'filament.mentor.pages.profit-page';

    public $saldoMentor;

    public function mount()
    {
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

        $this->saldoMentor = $saldoMentor;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Profit Transaksi Produk Digital';
    }

    protected function getFooterWidgets(): array
    {
        return [
            ProfitStatWidget::class,
            ProfitChartWidget::class,
            ProfitTableWidget::class,
        ];
    }
}
