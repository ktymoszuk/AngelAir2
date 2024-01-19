<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AvvisoNuovoUtente extends Mailable
{
    use Queueable, SerializesModels;

    public $mailNuovoUtente;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailNuovoUtente)
    {   
        
        if(is_null($mailNuovoUtente)){
            $this->mailNuovoUtente = '';
        }
        else{
            $this->mailNuovoUtente = $mailNuovoUtente;
        }

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Attenzione iscrizione di un nuovo utente',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails.avvisonuovoutente',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
