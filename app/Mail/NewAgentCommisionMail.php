<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewAgentCommisionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leasingRequest;

    public $agent;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($leasingRequest, $agent)
    {
        $this->leasingRequest = $leasingRequest;
        $this->agent = $agent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Новая заявка на выплату АВ!";

        return $this->from('agent.bestleasing@gmail.com')
        ->view('emails.agent_commission')
        ->subject($subject)
        ->with([
            'customer' => $this->leasingRequest->name, 
            'sum' => (int) preg_replace("/[^\d]/", "", $this->leasingRequest->leasing_amount) / 100 * $this->agent->ab_size,
            'agentName' => $this->agent->name
        ]);
    }
}
