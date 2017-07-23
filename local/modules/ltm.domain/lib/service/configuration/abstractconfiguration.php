<?php

namespace Ltm\Domain\Service\Configuration;

use Symfony\Component\Config\Definition\Processor;
use Ltm\Domain\Service\Configuration\Provider;

abstract class AbstractConfiguration implements LtmConfigurationInterface
{
    protected $configurationProvider;

    public function __construct(Provider\ProviderInterface $provider)
    {
        $this->configurationProvider = $provider;
    }

    public function getConfiguration()
    {
        $rawConfiguration = $this->configurationProvider->get();

        $processor = new Processor();

        return $processor->processConfiguration($this, [$rawConfiguration]);
    }
}
