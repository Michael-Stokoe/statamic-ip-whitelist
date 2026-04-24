<?php

namespace Stokoe\IpWhitelist\Services;

use Illuminate\Support\Facades\File;
use Stokoe\IpWhitelist\Models\WhitelistedIp;

class IpWhitelistService
{
    public function isAllowed(string $ip): bool
    {
        if (in_array($ip, config('ip-whitelist.default_allowed_ips', []))) {
            return true;
        }

        if (config('ip-whitelist.bypass_local') && app()->environment('local')) {
            return true;
        }

        $whitelistedIps = $this->getWhitelistedIps();
        
        foreach ($whitelistedIps as $whitelistedIp) {
            if ($this->matchesPattern($ip, $whitelistedIp['ip'])) {
                return true;
            }
        }

        return false;
    }

    public function addIp(string $ip, ?string $name = null): void
    {
        if (config('ip-whitelist.storage') === 'database') {
            WhitelistedIp::updateOrCreate(
                ['ip' => $ip],
                [
                    'name' => $name, 
                    'user_id' => auth()->id(),
                    'active' => true
                ]
            );
        } else {
            $ips = collect($this->readFileIps())
                ->reject(fn ($item) => ($item['ip'] ?? null) === $ip)
                ->values()
                ->all();

            $ips[] = [
                'ip' => $ip,
                'name' => $name,
                'user_id' => auth()->id(),
                'active' => true,
                'created_at' => now()->toISOString(),
            ];
            $this->saveToFile($ips);
        }
    }

    public function removeIp(string $ip): void
    {
        if (config('ip-whitelist.storage') === 'database') {
            WhitelistedIp::where('ip', $ip)->delete();
        } else {
            $ips = $this->getWhitelistedIps();
            $ips = array_filter($ips, fn($item) => $item['ip'] !== $ip);
            $this->saveToFile(array_values($ips));
        }
    }

    public function getWhitelistedIps(): array
    {
        if (config('ip-whitelist.storage') === 'database') {
            return WhitelistedIp::where('active', true)
                ->get()
                ->map(fn($ip) => [
                    'ip' => $ip->ip,
                    'name' => $ip->name,
                    'active' => $ip->active,
                    'added_by' => $ip->added_by,
                    'created_at' => $ip->created_at?->toISOString(),
                ])
                ->toArray();
        }

        $filePath = config('ip-whitelist.file_path');
        
        if (!File::exists($filePath)) {
            return [];
        }

        $ips = $this->readFileIps();
        
        return array_map(function($ip) {
            if (isset($ip['user_id'])) {
                $user = \Statamic\Facades\User::find($ip['user_id']);
                $ip['added_by'] = $user ? ($user->name() ?? $user->email()) : 'Unknown User';
            } else {
                $ip['added_by'] = null;
            }
            return $ip;
        }, $ips);
    }

    public function updateIp(string $oldIp, string $newIp, ?string $name = null): void
    {
        $this->removeIp($oldIp);
        $this->addIp($newIp, $name);
    }

    private function matchesPattern(string $ip, string $pattern): bool
    {
        if ($ip === $pattern) {
            return true;
        }

        if (strpos($pattern, '/') !== false) {
            return $this->ipInRange($ip, $pattern);
        }

        if (strpos($pattern, '*') !== false) {
            $regex = str_replace('\*', '.*', preg_quote($pattern, '/'));
            return (bool) preg_match("/^{$regex}$/", $ip);
        }

        return false;
    }

    private function ipInRange(string $ip, string $range): bool
    {
        list($subnet, $bits) = explode('/', $range);
        
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ip = ip2long($ip);
            $subnet = ip2long($subnet);
            $mask = -1 << (32 - $bits);
            $subnet &= $mask;
            return ($ip & $mask) == $subnet;
        }

        return false;
    }

    private function readFileIps(): array
    {
        $filePath = config('ip-whitelist.file_path');

        if (! File::exists($filePath)) {
            return [];
        }

        $content = File::get($filePath);

        return json_decode($content, true) ?: [];
    }

    private function saveToFile(array $ips): void
    {
        $filePath = config('ip-whitelist.file_path');
        $directory = dirname($filePath);
        
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        File::put($filePath, json_encode($ips, JSON_PRETTY_PRINT));
    }
}
