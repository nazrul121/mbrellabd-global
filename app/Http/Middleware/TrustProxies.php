<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{

    protected $proxies = '*'; // Trust all proxies

    protected $headers = Request::HEADER_X_FORWARDED_ALL;

    public function handle(Request $request, \Closure $next)
    {
        return parent::handle($request, $next);
    }

}
