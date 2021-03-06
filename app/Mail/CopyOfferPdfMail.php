<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Agent;
use App\User;

class CopyOfferPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $fileName)
    {
        $this->fileName = $fileName;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Ваш агент відправив КП на {$this->data['mark']} {$this->data['model']} {$this->data['advance']}% {$this->data['term']}міс {$this->data['currency']}";
        $agent = Agent::find($this->data['agentId']);
        $user = User::find($agent->user_id);
        return $this->from('agent.bestleasing@gmail.com')
                    ->view('emails.copy_offer_pdf')
                    ->subject($subject)
                    ->attach("pdf/{$this->fileName}", [
                        'as' => "{$this->fileName}",
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'fio' => "{$agent->name}", 
                        'client_email' => $this->data['email']
                    ]);
    }
}
