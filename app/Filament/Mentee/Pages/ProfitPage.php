<?php

namespace App\Filament\Mentee\Pages;

use Filament\Pages\Page;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Mentee\Widgets\ProfitStatWidget;
use App\Filament\Mentee\Widgets\ProfitChartWidget;
use App\Filament\Mentee\Widgets\ProfitTableWidget;

class ProfitPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string $view = 'filament.mentee.pages.profit-page';

    public $saldoMentee;

    public function mount()
    {
        $transaksiMentee = Transaksi::query()->whereReferralCode(Auth::user()->referral_code)->whereStatus('Sukses')->get();
        $saldoMentee = 0;
        foreach ($transaksiMentee as $transaksi) {
            $saldoMentee += $transaksi->total_harga * 0.15;
        }

        $this->saldoMentee = $saldoMentee;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Profit Keuntungan Transaksi';
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
