<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\Auth;
use App\Models\Role;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $action)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $actions = explode('|', $action);
        
            if ($roles = Role::find($user->role_id)) {
                if (! is_null($roles->permissions)) {
                    if (count(array_intersect($actions, $roles->permissions))) {
                        return $next($request);
                    }
                }
            }
        }
        
        return response()->json(['message' => 'Tidak ada otorisasi'], 401);
    }
}
