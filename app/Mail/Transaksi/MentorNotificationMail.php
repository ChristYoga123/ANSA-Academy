<?php

namespace App\Mail\Transaksi;

use App\Models\User;
use App\Models\Transaksi;
use App\Models\ProgramMentee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\MentoringPaket;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class MentorNotificationMail extends Mailable
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

        if($this->transaksi->transaksiable_type === ProgramMentee::class)
        {
            $programMentee = ProgramMentee::find($this->transaksi->transaksiable_id);
            
            if($programMentee->paketable_type === MentoringPaket::class)
            {
                $items[] = [
                    'judul' => MentoringPaket::find($programMentee->paketable_id)->mentoring->judul . ' - ' . MentoringPaket::find($programMentee->paketable_id)->label . ' - dengan mentor ' . User::find($programMentee->mentor_id)->name,
                    'tipe' => 'Mentoring',
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
            subject: 'Mentor Notification Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.transaksi.mentor-notification-mail',
            with: [
                'transaksi' => $this->transaksi,
                'items' => $this->items,
            ],
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
