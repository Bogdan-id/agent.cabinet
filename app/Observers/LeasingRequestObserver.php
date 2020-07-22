<?php

namespace App\Observers;

use App\Models\{
    LeasingRequest,
    Notification
};

class LeasingRequestObserver
{
    protected $oldStatus;
    
    protected $newStatus;

    /**
     * Handle the leasing request "created" event.
     *
     * @param  \App\LeasingRequest  $leasingRequest
     * @return void
     */
    public function created(LeasingRequest $leasingRequest)
    {
        if($leasingRequest->client_type_id == 1){
            $firstName = mb_substr($leasingRequest->first_name, 0, 1);
            $patronymic = mb_substr($leasingRequest->patronymic, 0, 1);
            $notificationTitle = "Створено нову заявку на лизінг на им'я {$leasingRequest->last_name} {$firstName}.{$patronymic}.";
        }elseif($leasingRequest->client_type_id == 2){
            $notificationTitle = "Створено нову заявку на лизінг на компанію {$leasingRequest->legal_info['company_name']}";
        }
        $notification = new Notification;
        $notification->agent_id = $leasingRequest->agent_id;
        $notification->title = $notificationTitle;
        $notification->save();
    }

    /**
     * Handle the leasing request "updating" event.
     *
     * @param  \App\LeasingRequest  $leasingRequest
     * @return void
     */
    public function updating(LeasingRequest $leasingRequest)
    {
        $this->oldStatus = $leasingRequest->status_id;
    }

    /**
     * Handle the leasing request "updated" event.
     *
     * @param  \App\LeasingRequest  $leasingRequest
     * @return void
     */
    public function updated(LeasingRequest $leasingRequest)
    {
        dd($this->oldStatus);
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
