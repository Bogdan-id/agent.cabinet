<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class BannedUser
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
            $user = User::find(Auth::user()->id);
      
           if($user->is_active === 1 || $user->is_active  === 0){
               return redirect('home');
           }
           
           return $next($request);
       }else{
           return $next($request);
       }
    }
}
