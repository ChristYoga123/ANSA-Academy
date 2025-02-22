<x-filament-panels::page>
    <p>
        Harap menghubungi admin untuk melakukan penarikan dana ke
        <a style="color: #FBBF24"
            href=https://wa.me/6283191260587?text=Halo%20Admin%20Saya%20Mentor%20{{ Auth::user()->name }}%20Ingin%20Melakukan%20Penarikan%20Dana%20Hasil%20Penjualan%20Produk%20Digital%20Sebesar%20Rp.{{ number_format($saldoMentor, 0, ',', '.') }}"
            target="_blank">
            nomor ini
        </a>
    </p>
</x-filament-panels::page>
