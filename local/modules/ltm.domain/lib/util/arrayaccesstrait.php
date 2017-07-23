<?php

namespace Ltm\Domain\Util;

trait ArrayAccessTrait
{
    protected $registry = [];

    public function offsetSet($instance, $clientObject)
    {
        $this->registry[$instance] = $clientObject;
    }

    public function offsetExists($instance)
    {
        return isset($this->registry[$instance]);
    }

    public function offsetUnset($instance)
    {
        unset($this->registry[$instance]);
    }

    public function offsetGet($instance)
    {
        if (!isset($this[$instance])) {
            $this[$instance] = $this->get($instance);
        }

        return isset($this[$instance]) ? $this->registry[$instance] : null;
    }
}
