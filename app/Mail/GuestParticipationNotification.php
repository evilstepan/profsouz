<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GuestParticipationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $participation;

    public function __construct($event, $participation)
    {
        $this->event = $event;
        $this->participation = $participation;
    }

    public function build()
    {
        return $this->subject('Подтверждение участия в мероприятии')
                    ->view('emails.guest-participation')
                    ->with([
                        'event' => $this->event,
                        'participation' => $this->participation
                    ]);
    }
} 