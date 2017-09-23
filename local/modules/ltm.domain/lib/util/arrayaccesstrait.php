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
        if (!isset($this->registry[$instance])) {
            $this->registry[$instance] = $this->get($instance);
        }

        return isset($this->registry[$instance]) ? $this->registry[$instance] : null;
    }
}
