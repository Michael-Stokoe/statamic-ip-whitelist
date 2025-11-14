<?php

namespace Stokoe\IpWhitelist\Console\Commands;

use Illuminate\Console\Command;
use Stokoe\IpWhitelist\Services\IpWhitelistService;

class ManageIpWhitelist extends Command
{
    protected $signature = 'ip-whitelist:manage 
                            {action : Action to perform (add, remove, list)}
                            {ip? : IP address to add or remove}
                            {--name= : Name/description for the IP address}';

    protected $description = 'Manage IP whitelist entries';

    protected $ipWhitelistService;

    public function __construct(IpWhitelistService $ipWhitelistService)
    {
        parent::__construct();
        $this->ipWhitelistService = $ipWhitelistService;
    }

    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'add':
                $this->addIp();
                break;
            case 'remove':
                $this->removeIp();
                break;
            case 'list':
                $this->listIps();
                break;
            default:
                $this->error('Invalid action. Use: add, remove, or list');
                return 1;
        }

        return 0;
    }

    private function addIp()
    {
        $ip = $this->argument('ip');
        
        if (!$ip) {
            $ip = $this->ask('Enter IP address to whitelist');
        }

        $name = $this->option('name') ?: $this->ask('Enter name/description (optional)', '');

        try {
            $this->ipWhitelistService->addIp($ip, $name);
            $this->info("Successfully added IP: {$ip}" . ($name ? " ({$name})" : ''));
        } catch (\Exception $e) {
            $this->error("Failed to add IP: {$e->getMessage()}");
            return 1;
        }
    }

    private function removeIp()
    {
        $ip = $this->argument('ip');
        
        if (!$ip) {
            $ip = $this->ask('Enter IP address to remove');
        }

        if ($this->confirm("Are you sure you want to remove IP: {$ip}?")) {
            try {
                $this->ipWhitelistService->removeIp($ip);
                $this->info("Successfully removed IP: {$ip}");
            } catch (\Exception $e) {
                $this->error("Failed to remove IP: {$e->getMessage()}");
                return 1;
            }
        }
    }

    private function listIps()
    {
        $ips = $this->ipWhitelistService->getWhitelistedIps();

        if (empty($ips)) {
            $this->info('No IP addresses in whitelist.');
            return;
        }

        $this->info('Whitelisted IP addresses:');
        $this->table(
            ['IP Address', 'Name', 'Created At'],
            array_map(function ($ip) {
                return [
                    $ip['ip'],
                    $ip['name'] ?: '-',
                    $ip['created_at'] ?? '-',
                ];
            }, $ips)
        );
    }
}
