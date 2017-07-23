<?php

namespace Ltm\Domain\Service\Provider;

use Ltm\Domain\Service\Configuration as Config;

class TemplateEngine implements \Pimple\ServiceProviderInterface
{
    protected $template;

    const SERVICE_ID = "template";

    public function __construct(Config\TemplateEngine $configuration)
    {
        $this->template = $configuration->getConfiguration()["class"];
    }

    public function register(\Pimple\Container $container)
    {
        if (!class_exists($this->template)) {
            throw new \UnexpectedValueException("Unexpected template class ".$this->template);
        }
        
        $container[static::SERVICE_ID] = new $this->template();
    }
}