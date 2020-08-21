<?php

namespace App\Http\Controllers;
use App\Http\Requests\FeedbackRequest;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Mail\FeedbackMail;
use Mail;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sendFeedBack(FeedbackRequest $request)
    {
        $data = $request->validated();
        $agent = Agent::find($data['agentId']);

        Mail::to(env('SUPPORT_MAIL'))->send(new FeedbackMail($agent, $data));

        return response()->json([
            'status' => 200
        ]);
    }
}
