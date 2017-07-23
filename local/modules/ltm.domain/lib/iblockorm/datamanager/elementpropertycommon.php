<?php

namespace Ltm\Domain\Util\IblockOrm\DataManager;

use Bitrix\Iblock;
use Bitrix\Main;
use Ltm\Domain\Util\IblockOrm\AbstractIblockIdGetterTrait;
use Ltm\Domain\Util\IblockOrm\PropertyProvider\Multiple as MultiplePropertyProvider;

/**
 * Абстрактный класс фейковой сущности для организации работы со свойствами
 * инфоблоков 1.0 в том же стиле, что и инфоблоки 2.0
 */
abstract class ElementPropertyCommon extends AbstractElementProperty
{
    use AbstractIblockIdGetterTrait;
    
    /** @inheritDoc */
    public static function getTableName() 
    {
        return Iblock\ElementTable::getTableName();
    }
    
    /** @inheritDoc */
    public static function getMap() 
    {
        return array_merge(
            array(
                new Main\Entity\IntegerField(
                    'IBLOCK_ELEMENT_ID',
                    array(
                        'primary' => true,
                        'column_name' => 'ID'
                    )
                )
            ),
            static::getPropertiesMap()
        );
    }
    
    /** @inheritDoc */
    protected static function createPropertiesMap(): array 
    {
        $model = static::getIblockModel();
        $provider = new MultiplePropertyProvider(__NAMESPACE__ . '\ElementProperty');
        $properties = $model->getProperties();
        
        $result = array();
        foreach($properties as $prop) {
            $result = array_merge(
                $result,
                $provider->getPropertyFields($prop)
            );
        }
        
        return array_filter($result);
    }
}