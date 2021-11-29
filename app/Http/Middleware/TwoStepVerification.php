<?php

namespace App\Http\Middleware;

use Closure;

class TwoStepVerification
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
        if ($request->session()->exists('2fa')) {
            return redirect('logout');
        }

        return $next($request);
    }
}
