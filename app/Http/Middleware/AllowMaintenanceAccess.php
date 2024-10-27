<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowMaintenanceAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->isDownForMaintenance()) {
            // Allow access to specific IPs or specific routes
            $allowedIPs = ['127.0.0.1'];
            if (!in_array($request->ip(), $allowedIPs) && !$this->isAllowedRoute($request)) {
                return response('Service Unavailable', 503);
            }
        }
        return $next($request);
    }

    protected function isAllowedRoute(Request $request)
    {
        $allowedRoutes = [
            'maintenance/up',
        ];
        return in_array($request->path(), $allowedRoutes);
    }
}
