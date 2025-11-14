<?php

namespace Stokoe\IpWhitelist\Tests;

use Stokoe\IpWhitelist\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
