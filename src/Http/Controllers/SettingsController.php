<?php

namespace Stokoe\IpWhitelist\Http\Controllers;

use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;

class SettingsController extends CpController
{
    public function index()
    {
        $this->authorize('manage ip whitelist');

        $settings = [
            'storage' => config('ip-whitelist.storage'),
            'bypass_local' => config('ip-whitelist.bypass_local'),
            'protected_routes' => config('ip-whitelist.protected_routes'),
            'default_allowed_ips' => config('ip-whitelist.default_allowed_ips'),
        ];

        return view('ip-whitelist::settings', compact('settings'));
    }
}
