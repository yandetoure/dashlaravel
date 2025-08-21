<?php declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\Reservation;

class ReservationCreatedDriver extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle réservation assignée'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_created_driver',
            with: [
                'reservation' => $this->reservation,
            ],
        );
    }
}

