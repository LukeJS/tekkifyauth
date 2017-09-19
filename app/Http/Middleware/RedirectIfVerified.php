<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfVerified
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
        if ($request->user->minecraftAccount != null) {
            return redirect('/');
        }

        return $next($request);
    }
}
