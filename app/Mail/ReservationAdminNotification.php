<?php declare(strict_types=1);

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationAdminNotification extends Mailable
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
            'created' => 'Nouvelle réservation créée',
            'confirmed' => 'Réservation confirmée',
            'cancelled' => 'Réservation annulée',
            'updated' => 'Réservation modifiée',
            default => 'Notification de réservation'
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
            view: 'emails.reservation-admin-notification',
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
