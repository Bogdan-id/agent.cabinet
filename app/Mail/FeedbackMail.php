<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Agent;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    public $agent;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Agent $agent, array $message)
    {
        $this->message = $message;
        $this->agent = $agent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {      
        //dd($this->message['message']);
        return $this->from('agent.bestleasing@gmail.com')
        ->view('emails.feedback')
        ->subject($this->message['subject'])
        ->with([
            'agentName' => $this->agent->name, 
            'email' => $this->agent->user->email,
            'phone' => $this->agent->user->phone,
            'messageText' => $this->message['message']
        ]);
    }
}
