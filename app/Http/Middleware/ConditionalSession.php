<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Middleware\StartSession;
use Symfony\Component\HttpFoundation\Response;

class ConditionalSession
{
    public function handle($request, Closure $next)
    {
        // Start the session before processing the request
        return app(StartSession::class)->handle($request, function () use ($request, $next) {
            $response = $next($request);
            
            // Check if the response status is 404 or 500
            if ($response instanceof Response && ($response->status() === 404 || $response->status() === 500)) {
                // Set session data for errors
                session()->put('error_message', 'An error occurred.'); // Customize your message
            }

            return $response; // Return the original response
        });
    }
}
