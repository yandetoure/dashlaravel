<?php declare(strict_types=1);

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationClientNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $action;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation, string $action)
    {
        $this->reservation = $reservation;
        $this->action = $action;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->action) {
            'confirmed' => 'Votre réservation a été confirmée - CPRO Services',
            'cancelled' => 'Votre réservation a été annulée - CPRO Services',
            default => 'Notification de réservation - CPRO Services'
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-client-notification',
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
