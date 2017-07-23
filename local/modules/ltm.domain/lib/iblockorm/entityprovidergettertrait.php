<?php

namespace Ltm\Domain\Util\IblockOrm;

trait EntityProviderGetterTrait
{
    use AbstractIblockIdGetterTrait;
    
    /**
     * Возвращает объект провайдера сущностей для данного инфоблока
     * 
     * @return EntityProviderInterface
     */
    public static function getProvider()
    {
        return Manager::getInstance()->getProvider(
            static::getIblockId(),
            static::getProviderCreator()
        );
    }
    
    /**
     * Возвращает функцию, которая создаст провайдер для нужного инфоблока
     * 
     * @return callable|null
     */
    public static function getProviderCreator()
    {
        return null;
    }
}