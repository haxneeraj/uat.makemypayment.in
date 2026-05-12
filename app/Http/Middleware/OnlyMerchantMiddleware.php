<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlyMerchantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // check if user is authenticated
        if(!auth()->check())
        {
            return redirect()->route('login');
        }

        // check if user is admin or admin staff
        if(auth()->user()->role !== 'merchant')
        {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
