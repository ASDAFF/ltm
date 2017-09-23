<?php

namespace Ltm\Domain\IblockOrm;

use Ltm\Domain\Util\SingletonTrait;

class Manager
{
    use SingletonTrait;
    
    /**
     * Внутреннее хранилище объектов провайдеров сущностей
     * 
     * @var array<EntityProviderInterface>
     */
    protected $providerStorage = array();
    
    /**
     * Возвращает объект провайдера сущностей для инфоблока. В случае, если
     * такого провайдера пока нет, будет осуществлено создание провайдера.
     * Создание провайдера может быть выполнено функцией, переданной 
     * в аргумент `$createCallback`. Если данный аргумент пустой, будет создан
     * объект класса провайдера по умолчанию.
     * 
     * @param int $iblockId
     * @param callable $createCallback
     * 
     * @return \Ltm\Domain\IblockOrm\EntityProviderInterface
     */
    public function getProvider(int $iblockId, callable $createCallback = null): EntityProviderInterface 
    {
        if(empty($this->providerStorage[$iblockId])) {
            if(empty($createCallback)) {
                $createCallback = $this->getDefaultCreateProviderCallback();
            }
            
            $this->registerProvider($iblockId, call_user_func($createCallback, $iblockId));
        }
        
        return $this->providerStorage[$iblockId];
    }
    
    /**
     * Регистрирует объект провайдера сущностей для инфоблока
     * 
     * @param int $iblockId
     * @param \Ltm\Domain\IblockOrm\EntityProviderInterface $provider
     */
    public function registerProvider(int $iblockId, EntityProviderInterface $provider) 
    {
        $this->providerStorage[$iblockId] = $provider;
    }
    
    /**
     * Выполняет создание провайдера сущностей для инфоблока. Если передан класс 
     * провайдера, произойдет создание объекта этого класса. Иначе — создан
     * объект провайдера по умолчанию.
     * 
     * @param int $iblockId
     * @param string $providerClassName
     * 
     * @return \Ltm\Domain\IblockOrm\EntityProviderInterface
     * 
     * @uses self::getDefaultProviderClassName() Получение названия класса провайдера по умолчанию
     */
    protected function createProvider(int $iblockId, string $providerClassName = null): EntityProviderInterface 
    {
        if(empty($providerClassName)) {
            $providerClassName = $this->getDefaultProviderClassName();
        }
        
        return new $providerClassName($iblockId);
    }
    
    /**
     * Возвращает функцию создания провайдера по умолчанию
     * 
     * @return callable
     */
    protected function getDefaultCreateProviderCallback(): callable 
    {
        return array($this, "createProvider");
    }
    
    /**
     * Возвращает имя класса создания провайдера по умолчанию
     * 
     * @return string
     */
    protected function getDefaultProviderClassName(): string 
    {
        return DefaultEntityProvider::class;
    }
}