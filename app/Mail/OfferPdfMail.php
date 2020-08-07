<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Agent;
use App\User;


class OfferPdfMail extends Mailable
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
        $subject = "Комерційна пропозиція {$this->data['mark']} {$this->data['model']} {$this->data['advance']}% {$this->data['term']}міс {$this->data['currency']}";
        $agent = Agent::find($this->data['agentId']);
        $user = User::find($agent->user_id);
       //dd($this->data['leasingObjType']);
        return $this->from('agent.bestleasing@gmail.com')
                    ->view('emails.offer_pdf')
                    ->subject($subject)
                    ->attach("pdf/{$this->fileName}", [
                        'as' => "{$this->fileName}.pdf",
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'fio' => "{$agent->first_name} {$agent->last_name} {$agent->patronymic}", 
                        'phone' => $user->phone,
                        'email'=> $user->email,
                        'mark' => $this->data['mark'],
                        'model' => $this->data['model'],
                        'price' => $this->data['price'],
                        'leasingObjectType' => $this->data['leasingObjType']
                    ]);
    }
}
