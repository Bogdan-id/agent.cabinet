<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Auth;

class NewDataEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notifications;

    public $leasingRequests;

    public $agentCommissions;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $agent = Auth::user()->agent;
        $this->notifications = $agent->notifications;
        $this->leasingRequests = $agent->leasingRequests;
        $this->agentCommissions = json_decode($agent->commissions->toJson());
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('test');
    }
}
