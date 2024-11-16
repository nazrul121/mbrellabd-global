<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class CustomThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure  $next
     * @param int  $maxAttempts
     * @param int  $decayMinutes
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 1, $decayMinutes = 1)
    {
        // Define a unique key for rate limiting (we can use the user's IP for simplicity)
        $key = $this->resolveRequestSignature($request);

        // Check if the request has exceeded the allowed number of attempts within the given time frame
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            // Calculate the wait time before the next attempt is allowed
            $seconds = RateLimiter::availableIn($key);
   
            // Return a custom response with the wait time (429 error)
            return response()->view('errors.429', ['retryAfter' => $seconds], 429);

            return response()->json([
                'error' => 'Too many requests. Please try again in ' . $seconds . ' seconds.'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        // Allow the request to proceed and increment the attempt counter
        RateLimiter::hit($key, $decayMinutes * 60); // Convert decay minutes to seconds

        return $next($request);
    }

    /**
     * Get the unique key for the given request.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function resolveRequestSignature(Request $request)
    {
        // Use the client's IP address to create a unique key (adjust as needed)
        return $request->ip();
    }
}
