<?php

namespace Ltm\Domain\Service;

abstract class AbstractService
{
    public function __construct(array $instancePoolConfiguration)
    {
        $this->availableInstances = $instancePoolConfiguration;
    }

    protected $availableInstances;

    protected function getInstanceConfiguration($instanceName)
    {
        if (!array_key_exists($instanceName, $this->availableInstances)) {
            throw new \UnexpectedValueException("Unexpected configuration instance name \"$instanceName\"");
        }

        return $this->availableInstances[$instanceName];
    }

    abstract protected function get($instanceName);
}
