<?php

namespace Stokoe\IpWhitelist\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Stokoe\IpWhitelist\Services\IpWhitelistService;
use Stokoe\IpWhitelist\ServiceProvider;

class IpWhitelistServiceTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        config(['ip-whitelist.storage' => 'file']);
        config(['ip-whitelist.file_path' => storage_path('testing/ip-whitelist.json')]);
        config(['ip-whitelist.bypass_local' => false]);
        config(['ip-whitelist.default_allowed_ips' => ['127.0.0.1']]);
    }

    public function test_can_add_ip_to_whitelist()
    {
        $service = new IpWhitelistService();
        
        $service->addIp('192.168.1.100', 'Test IP');
        
        $ips = $service->getWhitelistedIps();
        
        $this->assertCount(1, $ips);
        $this->assertEquals('192.168.1.100', $ips[0]['ip']);
        $this->assertEquals('Test IP', $ips[0]['name']);
    }

    public function test_can_check_if_ip_is_allowed()
    {
        $service = new IpWhitelistService();
        
        // Default allowed IP should be allowed
        $this->assertTrue($service->isAllowed('127.0.0.1'));
        
        // Non-whitelisted IP should not be allowed
        $this->assertFalse($service->isAllowed('192.168.1.100'));
        
        // Add IP and check again
        $service->addIp('192.168.1.100');
        $this->assertTrue($service->isAllowed('192.168.1.100'));
    }

    public function test_can_remove_ip_from_whitelist()
    {
        $service = new IpWhitelistService();
        
        $service->addIp('192.168.1.100', 'Test IP');
        $this->assertCount(1, $service->getWhitelistedIps());
        
        $service->removeIp('192.168.1.100');
        $this->assertCount(0, $service->getWhitelistedIps());
    }

    public function test_supports_cidr_notation()
    {
        $service = new IpWhitelistService();
        
        $service->addIp('192.168.1.0/24', 'Network Range');
        
        $this->assertTrue($service->isAllowed('192.168.1.100'));
        $this->assertTrue($service->isAllowed('192.168.1.1'));
        $this->assertFalse($service->isAllowed('192.168.2.100'));
    }

    public function test_supports_wildcard_notation()
    {
        $service = new IpWhitelistService();
        
        $service->addIp('192.168.1.*', 'Wildcard Range');
        
        $this->assertTrue($service->isAllowed('192.168.1.100'));
        $this->assertTrue($service->isAllowed('192.168.1.1'));
        $this->assertFalse($service->isAllowed('192.168.2.100'));
    }
}
