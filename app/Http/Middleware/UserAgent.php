<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Agent;
use App\User;

class UserAgent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()){
            $agent = Agent::where('user_id', '=', Auth::user()->id)->first();
           if(!$agent){
               return redirect('finish-register');
           }

           return $next($request);
       }else{
           
           return $next($request);
       }      
    }
}
