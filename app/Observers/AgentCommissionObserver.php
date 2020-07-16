<?php

namespace App\Observers;

use App\Models\{
    AgentCommission,
    Notification
};

class AgentCommissionObserver
{
    /**
     * Handle the agent commission "created" event.
     *
     * @param  \App\AgentCommission  $agentCommission
     * @return void
     */
    public function created(AgentCommission $agentCommission)
    {
        $notification = new Notification;
        $notification->agent_id = $agentCommission->agent_id;
        $notification->title = "Створена заявка на виплату АВ №{$agentCommission->id}";
        $notification->save();
    }

    /**
     * Handle the agent commission "updated" event.
     *
     * @param  \App\AgentCommission  $agentCommission
     * @return void
     */
    public function updated(AgentCommission $agentCommission)
    {
        $notification = new Notification;
        $notification->agent_id = $agentCommission->agent_id;
        $notification->title = "Заявку на виплату АВ №{$agentCommission->id}, выплачено";
        $notification->save();
    }

    /**
     * Handle the agent commission "deleted" event.
     *
     * @param  \App\AgentCommission  $agentCommission
     * @return void
     */
    public function deleted(AgentCommission $agentCommission)
    {
        //
    }

    /**
     * Handle the agent commission "restored" event.
     *
     * @param  \App\AgentCommission  $agentCommission
     * @return void
     */
    public function restored(AgentCommission $agentCommission)
    {
        //
    }

    /**
     * Handle the agent commission "force deleted" event.
     *
     * @param  \App\AgentCommission  $agentCommission
     * @return void
     */
    public function forceDeleted(AgentCommission $agentCommission)
    {
        //
    }
}
