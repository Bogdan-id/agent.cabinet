<?php

namespace App\Observers;

use App\Models\{
    LeasingRequest,
    Notification
};

class LeasingRequestObserver
{
    /**
     * Handle the leasing request "created" event.
     *
     * @param  \App\LeasingRequest  $leasingRequest
     * @return void
     */
    public function created(LeasingRequest $leasingRequest)
    {
        // if(){

        // }
        $notification = new Notification;
        $notification->agent_id = $leasingRequest->agent_id;
        $notification->title = "Створено нову заявку на лизінг на {$leasingRequest->first_name}";
        $notification->save();
       // dd($leasingRequest);
    }

    /**
     * Handle the leasing request "updated" event.
     *
     * @param  \App\LeasingRequest  $leasingRequest
     * @return void
     */
    public function updated(LeasingRequest $leasingRequest)
    {
        //
    }

    /**
     * Handle the leasing request "deleted" event.
     *
     * @param  \App\LeasingRequest  $leasingRequest
     * @return void
     */
    public function deleted(LeasingRequest $leasingRequest)
    {
        //
    }

    /**
     * Handle the leasing request "restored" event.
     *
     * @param  \App\LeasingRequest  $leasingRequest
     * @return void
     */
    public function restored(LeasingRequest $leasingRequest)
    {
        //
    }

    /**
     * Handle the leasing request "force deleted" event.
     *
     * @param  \App\LeasingRequest  $leasingRequest
     * @return void
     */
    public function forceDeleted(LeasingRequest $leasingRequest)
    {
        //
    }
}
