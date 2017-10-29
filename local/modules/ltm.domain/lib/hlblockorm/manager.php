<?php

namespace Ltm\Domain\HlblockOrm;

use Ltm\Domain\Util\SingletonTrait;

class Manager
{
    use SingletonTrait;
    
    protected $providerStorage = array();

    /**
     * @param string $entityName
     * @param callable|null $createCallback
     * @return EntityProvider\DefaultProvider
     */
    public function getProvider(string $entityName,  callable $createCallback = null)
    {
        if(empty($this->providerStorage[$entityName])) {
            if(empty($createCallback)) {
                $createCallback = $this->getDefaultCreateProviderCallback();
            }
            
            $this->registerProvider($entityName, call_user_func($createCallback, $entityName));
        }
        
        return $this->providerStorage[$entityName];
    }
    
    public function registerProvider(string $entityName, $provider)
    {
        $this->providerStorage[$entityName] = $provider;
    }
    
    protected function getDefaultCreateProviderCallback(): callable
    {
        return array($this, 'createProvider');
    }
    
    protected function createProvider($entityName)
    {
        return new EntityProvider\DefaultProvider($entityName);
    }
}
