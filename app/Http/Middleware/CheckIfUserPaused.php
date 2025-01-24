<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfUserPaused
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::guard('web')->check() && Auth::user()->user_role === 2) {

            if (Auth::guard('web')->user()->status === 3) {
                return redirect()->back()->with('error', 'Your account is currently paused. Access denied.');
            }
            if (Auth::guard('web')->user()->status === 2) {
                return redirect()->back()->with('error', 'Your have cancelled subscription. Access denied.');
            }
        }

        return $next($request);
    }
}
