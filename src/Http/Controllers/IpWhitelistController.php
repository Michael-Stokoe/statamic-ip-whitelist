<?php

namespace Stokoe\IpWhitelist\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;
use Stokoe\IpWhitelist\Helpers\IpValidator;
use Stokoe\IpWhitelist\Services\IpWhitelistService;

class IpWhitelistController extends CpController
{
    protected $ipWhitelistService;

    public function __construct(IpWhitelistService $ipWhitelistService)
    {
        $this->ipWhitelistService = $ipWhitelistService;
    }

    public function index()
    {
        $this->authorize('manage ip whitelist');

        $ips = $this->ipWhitelistService->getWhitelistedIps();
        $currentIp = request()->ip();

        return view('ip-whitelist::index', compact('ips', 'currentIp'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage ip whitelist');

        $request->validate([
            'ip' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!IpValidator::isValidIp($value)) {
                        $fail('The IP address format is invalid. Use exact IP (192.168.1.1), CIDR notation (192.168.1.0/24), or wildcards (192.168.1.*).');
                    }
                },
            ],
            'name' => 'nullable|string|max:255',
        ]);

        $normalizedIp = IpValidator::normalizeIp($request->ip);
        
        try {
            $this->ipWhitelistService->addIp($normalizedIp, $request->name);
            return response()->json(['success' => true, 'message' => 'IP address added successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $ip)
    {
        $this->authorize('manage ip whitelist');

        $request->validate([
            'ip' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!IpValidator::isValidIp($value)) {
                        $fail('The IP address format is invalid. Use exact IP (192.168.1.1), CIDR notation (192.168.1.0/24), or wildcards (192.168.1.*).');
                    }
                },
            ],
            'name' => 'nullable|string|max:255',
        ]);

        $normalizedIp = IpValidator::normalizeIp($request->ip);
        
        try {
            $this->ipWhitelistService->updateIp($ip, $normalizedIp, $request->name);
            return response()->json(['success' => true, 'message' => 'IP address updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function destroy($ip)
    {
        $this->authorize('manage ip whitelist');

        try {
            $this->ipWhitelistService->removeIp($ip);
            return response()->json(['success' => true, 'message' => 'IP address removed successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
