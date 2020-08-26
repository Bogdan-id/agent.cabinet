<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function leasingrequestsReport($agentId, $year)
    {
        $agent = Agent::find($agentId);
        $leasingRequests = $agent->leasingRequests()->where('created_at', '>', "$year-01-01 00:00:00")
                            ->where('created_at', '<', "{$year}-12-21 23:59:59")
                            ->get()
                            ->groupBy([function($d) {
            return Carbon::parse($d->created_at)->format('m');
        }, 'status_id']);
        $result  = $leasingRequests->mapWithKeys(function ($group, $key) {
            return [$key => $group->mapWithKeys(function ($item, $key) {
                return [$key => $item->sum('price_brutto')];
            })];
        });
        $inWork  = $result->map(function ($group) {
            $inWork = $group->get(1) + $group->get(2) + $group->get(3) + $group->get(4);
            $group->forget(1);
            $group->forget(2);
            $group->forget(3);
            $group->forget(4);
            $group->put('inWork', $inWork);
        });
       
        return  response()->json($result);
    }
}
