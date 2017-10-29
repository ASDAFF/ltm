<?php

namespace Ltm\Domain\Util;

trait SingletonTrait
{
    protected static $instance = array();
    
    protected function __construct() 
    {
    }
    
    final public static function getInstance()
    {
        $className = static::class;
        
        if(empty(static::$instance[$className])) {
            static::$instance[$className] = new $className;
        }
        
        return static::$instance[$className];
    }
}

