<?php

namespace Ltm\Domain\Service\Provider;

use Ltm\Domain\Service;
use Ltm\Domain\Service\Configuration;

class MySql implements \Pimple\ServiceProviderInterface
{
    const SERVICE_ID = "mysql";

    public function register(\Pimple\Container $container)
    {
        $container[static::SERVICE_ID] = new Service\MySql();
    }
}
