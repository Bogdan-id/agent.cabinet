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

        $done  = $result->map(function ($group) {
            $done = $group->get(5) + $group->get(6) + $group->get(7);
            $group->forget(5);
            $group->forget(6);
            $group->forget(7);
            $group->put(5, $done);
        });
       
        return  response()->json($result);
    }

    public function agentCommissionsReport($agentId, $year)
    {
        $agent = Agent::find($agentId);
        $agentCommissions = $agent->commissions()->where('created_at', '>', "$year-01-01 00:00:00")
                            ->where('created_at', '<', "{$year}-12-21 23:59:59")
                            ->get()
                            ->groupBy([function($d) {
            return Carbon::parse($d->created_at)->format('m');
        }]);
        $result  = $agentCommissions->mapWithKeys(function ($group, $key) {
            $notPaid = $group->where('status', '=', 'not_paid');
            $paid = $group->where('status', '=', 'paid');
            return [$key => [
                    'ac_sum' => $group->sum('sum'),
                    'price_brutto_sum' => $group->sum('price_brutto'),
                    'not_paid_sum' => $notPaid->sum('sum'),
                    'paid_sum' => $paid->sum('sum')
                ]];
            });    
        $result->put('max_price_brutto_sum', $result->max('price_brutto_sum'));
        $result->put('max_sum', $result->max('ac_sum'));
        $result->put('min_sum', $result->min('ac_sum'));
        $result->put('min_price_brutto_sum', $result->min('price_brutto_sum'));

        return response()->json($result);
    }

    public function getAgentCommissionsReportYears($agentId)
    {
        $years = ['2020'];
        $agent = Agent::find($agentId);
        $agentCommissions = $agent->commissions()->orderBy('created_at', 'DESC')->first();
        if($agentCommissions)
        {
            $startYear = '2020';
            $years = range($startYear, $agentCommissions->created_at->format('Y'));
        }
        
        return response()->json($years);                 
    }

    public function getLeasingRequestsReportYears($agentId)
    {
        $years = ['2020'];
        $agent = Agent::find($agentId);
        $leasingRequest = $agent->leasingRequests()->orderBy('created_at', 'DESC')->first();
        if($leasingRequest)
        {
            $startYear = '2020';
            $years = range($startYear, $leasingRequest->created_at->format('Y'));
            
        }
        
        return response()->json($years);                 
    }
}
