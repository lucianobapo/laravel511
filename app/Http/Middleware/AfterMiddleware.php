<?php

namespace App\Http\Middleware;

use App\Models\Traffic;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AfterMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Perform action
        if (strpos(url(), 'delivery.')!==false && env('APP_ENV')=='production')
            return Redirect::to('https://delivery24horas.com', 301);
        return $response;
    }
}