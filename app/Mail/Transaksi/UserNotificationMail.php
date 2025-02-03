<?php

namespace App\Mail\Transaksi;

use App\Models\User;
use App\Models\Event;
use App\Models\Transaksi;
use App\Models\ProdukDigital;
use App\Models\ProgramMentee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\KelasAnsaPaket;
use App\Models\MentoringPaket;
use App\Models\ProofreadingPaket;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transaksi $transaksi;
    public array $items;
    /**
     * Create a new message instance.
     */
    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
        $this->items = $this->resolveTransactionItems();
    }

    private function resolveTransactionItems()
    {
        $items = [];

        // polymorphic relationship
        if($this->transaksi->transaksiable_type === Event::class)
        {
            $items[] = [
                'judul' => Event::find($this->transaksi->transaksiable_id)->judul,
                'tipe' => 'Event',
                'harga' => $this->transaksi->total_harga, 
            ];
        } elseif($this->transaksi->transaksiable_type === ProdukDigital::class)
        {
            $items[] = [
                'judul' => ProdukDigital::find($this->transaksi->transaksiable_id)->judul,
                'tipe' => 'Produk Digital',
                'harga' => $this->transaksi->total_harga,
            ];
        } elseif($this->transaksi->transaksiable_type === ProgramMentee::class)
        {
            $programMentee = ProgramMentee::find($this->transaksi->transaksiable_id);
            
            if($programMentee->paketable_type === MentoringPaket::class)
            {
                $items[] = [
                    'judul' => MentoringPaket::find($programMentee->paketable_id)->mentoring->judul . ' - ' . MentoringPaket::find($programMentee->paketable_id)->label . ' - dengan mentor ' . User::find($programMentee->mentor_id)->name,
                    'tipe' => 'Mentoring',
                    'harga' => $this->transaksi->total_harga,
                ];
            }elseif($programMentee->paketable_type === KelasAnsaPaket::class)
            {
                $items[] = [
                    'judul' => KelasAnsaPaket::find($programMentee->paketable_id)->kelasAnsa->judul . ' - ' . KelasAnsaPaket::find($programMentee->paketable_id)->label,
                    'tipe' => 'Kelas Ansa',
                    'harga' => $this->transaksi->total_harga,
                ];
            }elseif($programMentee->paketable_type === ProofreadingPaket::class)
            {
                $items[] = [
                    'judul' => ProofreadingPaket::find($programMentee->paketable_id)->proofreading->judul . ' - ' . ProofreadingPaket::find($programMentee->paketable_id)->label,
                    'tipe' => 'Proofreading',
                    'harga' => $this->transaksi->total_harga,
                ];
            }
        }

        return $items;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Pembelian ANSA Academy',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.transaksi.user-notification-mail',
            with: [
                'transaksi' => $this->transaksi,
                'items' => $this->items,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
