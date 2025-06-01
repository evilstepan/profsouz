<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventParticipationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $participantData;

    public function __construct($event, $participantData)
    {
        $this->event = $event;
        $this->participantData = $participantData;
    }

    public function build()
    {
        return $this->subject('Подтверждение участия в мероприятии: ' . $this->event->name)
                    ->view('emails.event-participation');
    }
} 