<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        if(Auth::guard('admin')->check()){
            $user = Auth::guard('admin')->user();
        }else{
            $user = Auth::user();
        }
        
        // Check if the user is authenticated and has the required permission
        if (!$user || !$user->hasPermission($permission)) {
            abort(403, 'Access Denied: Permission required.');
        }
        return $next($request);
    }
}
