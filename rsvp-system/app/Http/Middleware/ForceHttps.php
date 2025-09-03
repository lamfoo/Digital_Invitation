<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS in production or when behind a proxy
        if (!$request->isSecure() && 
            (app()->environment('production') || 
             $request->header('X-Forwarded-Proto') === 'https' ||
             $request->header('CF-Visitor'))) {
            
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}