<?php

namespace Ltm\Domain\Service\Provider;

use Ltm\Domain\Service;
use Ltm\Domain\Service\Configuration as Config;

class Configuration implements \Pimple\ServiceProviderInterface
{
    protected $providers = [];

    const SERVICE_ID = "configuration";

    public function __construct(Config\Configuration $configuration)
    {
        $providers = $configuration->getConfiguration();

        foreach ($providers as $provider) {
            if (!class_exists($provider)) {
                throw new \UnexpectedValueException("Unexpected configuration provider \"$provider\"");
            }

            $this->providers[] = new $provider();
        }
    }

    public function register(\Pimple\Container $container)
    {
        $container[static::SERVICE_ID] = new Service\Configuration($this->providers);
    }
}
