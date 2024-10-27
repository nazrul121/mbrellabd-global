<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

   public function handle(Request $request, Closure $next, ...$guards)
   {
    // Set default guard if none provided
    $guards = empty($guards) ? [null] : $guards;

    // Check for authenticated user in the specified guards
    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            // Get the authenticated user
            $user = Auth::guard($guard)->user();

            // Redirect based on user type if authenticated
            switch ($user->user_type_id) {
                case 1:
                    return redirect()->route('superAdmin.dashboard');
                case 2:
                    return redirect()->route('admin.dashboard');
                case 3:
                    return redirect()->route('staff.dashboard');
                case 4:
                    return redirect()->route('customer.dashboard');
                default:
                    // Fallback to home route if user type is unknown
                    return redirect(RouteServiceProvider::HOME);
            }
        }
    }

    // If the user is not authenticated, proceed with the request
    return $next($request);
  }

}
