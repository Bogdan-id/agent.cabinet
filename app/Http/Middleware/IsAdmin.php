<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;
class IsAdmin
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
        if (Auth::user()) {

            $user = User::find(Auth::user()->id);
      
           if($user->is_admin === 1){
                return $next($request);
           }
        }

        abort(404);
    }
}
