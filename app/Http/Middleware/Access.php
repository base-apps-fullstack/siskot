<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\Auth;

class Access
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
        $auth = Auth::info();
        if ($auth) {
            if (access_module($request->path(), $auth) == false) {
                return response()->json(['message' => 'Tidak ada otorisasi'], 401);
            }
        }

        return $next($request);
    }
}
