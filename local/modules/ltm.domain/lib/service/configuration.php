<?php

namespace Ltm\Domain\Service;

use Ltm\Domain\Util\ArrayAccessTrait;

class Configuration implements \ArrayAccess
{
    use ArrayAccessTrait;

    protected $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    protected function get($parameter)
    {
        $value = null;

        foreach ($this->providers as $provider) {
            try {
                $value = $provider->get($parameter);
            } catch (\UnexpectedValueException $e) {
                continue;
            }

            break;
        }

        return $value;
    }
}
