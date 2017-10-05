<?php

namespace Ltm\Domain\IblockOrm\DataManager;

use Bitrix\Main;

/**
 * Абстрактный табличный класс для сущностей свойств. Реализует внутреннее 
 * хранилище полей свойств, чтобы их генерация происходила только один раз.
 * 
 * В потомках должен быть переопределен `createPropertiesMap()`
 */
abstract class AbstractElementProperty extends Main\Entity\DataManager
{
    /**
     * @var array<string,array<\Bitrix\Main\Entity\Field>>
     */
    protected static $properties = array();
    
    /**
     * Возвращает набор полей из внутреннего хранилища. Если он пуст, вызывает 
     * `self::createPropertiesMap()` для получения набора и сохраняет эти данные
     * в хранилище.
     * 
     * @return array
     * @uses self::createPropertiesMap()
     */
    public static function getPropertiesMap(): array
    {
        if(!isset(static::$properties[static::class])) {
            static::$properties[static::class] = static::createPropertiesMap();
        }
        
        return static::$properties[static::class];
    }
    
    /**
     * Создает набор полей свойств
     * @return array<\Bitrix\Main\Entity\Field>
     */
    abstract protected static function createPropertiesMap(): array;
}