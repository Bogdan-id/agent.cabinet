<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Комерційна пропозиція';

        return $this->from('agent.bestleasing@gmail.com')
                    ->view('emails.offer_pdf')
                    ->subject($subject)
                    ->attach("pdf/{$this->fileName}", [
                        'as' => "{$this->fileName}.pdf",
                        'mime' => 'application/pdf',
                    ]);
    }
}
