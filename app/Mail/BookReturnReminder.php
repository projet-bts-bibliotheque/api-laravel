<?php

namespace App\Mail;

use App\Models\BooksReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookReturnReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(BooksReservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Rappel : Livre à retourner')
            ->markdown('emails.books.return-reminder', [
                'url' => url('/profile'),
                'reservation' => $this->reservation
            ]);
    }
}