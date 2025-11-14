<?php

namespace Stokoe\IpWhitelist\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stokoe\IpWhitelist\Services\IpWhitelistService;

class IpWhitelistMiddleware
{
    protected $ipWhitelistService;

    public function __construct(IpWhitelistService $ipWhitelistService)
    {
        $this->ipWhitelistService = $ipWhitelistService;
    }

    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();
        
        if (!$this->ipWhitelistService->isAllowed($clientIp)) {
            abort(403, 'Access denied. Your IP address is not whitelisted.');
        }

        return $next($request);
    }
}
