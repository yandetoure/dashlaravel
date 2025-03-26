<?php declare(strict_types=1); 

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\Reservation; 

class ReservationCreatedclient extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    /**
     * Créer une nouvelle instance de message.
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Obtenir l'enveloppe du message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre réservation est en attente de confirmation'
        );
    }

    /**
     * Définir le contenu du message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_created',
            with: [

                'reservation' => $this->reservation,
                'client' => $this->reservation->client,
                'chauffeur' => $this->reservation->carDriver->chauffeur
            ],
        );
    }
}
