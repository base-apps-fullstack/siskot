<?php

namespace App\Http\Middleware;

use Closure;

class Api
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
        if ($request->header('Accept') != 'application/json') :
            return redirect()->route('home');
        endif;

        return $next($request);
    }
}
