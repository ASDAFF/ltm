<?php

namespace Ltm\Domain\Service\Configuration\Provider;

use Bitrix\Main\Config;

class BitrixSettings implements ProviderInterface
{
    private $configurationKey;

    public function __construct($configurationKey)
    {
        $this->configurationKey = $configurationKey;
    }

    public function get()
    {
        return Config\Configuration::getValue($this->configurationKey);
    }
}
