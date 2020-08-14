<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Agent,
    Notification
};
use App\Events\NewDataEvent;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAgentNotifications($id)
    {
        $agent = Agent::find($id);
        $agentNotifications = $agent->notifications;

        return response()->json($agentNotifications);
    }

    public function delete($id)
    {
        $notification = Notification::find($id);
        abort_if(!$notification, 422, 'Повідомлення не знайдено!');
        $notification->delete();

        return response()->json([
            'status' => 200
        ]);
    }

    public function checkingNotifications(Request $request)
    {
        $data = $request->post();
        foreach($data['notifications'] as $notificationId){
            $notification = Notification::find($notificationId);
            if($notification){
                $notification->update([
                    'status' => 'checked'
                ]);
            }      
        }
        event(new NewDataEvent());
        
        return response()->json([
            'status' => 200
        ]);
    }
}
