<?php

namespace Ltm\Domain\Util\IblockOrm\DataManager;

use Bitrix\Main;
use Bitrix\Main\Entity;
use Ltm\Domain\Util\IblockOrm\EntityProviderGetterTrait;
use Ltm\Domain\Util\IblockOrm\PropertyProvider;

/**
 * Абстрактный класс для таблиц значений единичных свойств инфоблоков 2.0
 */
abstract class ElementPropertySingle extends AbstractElementProperty
{
    use EntityProviderGetterTrait;
    
    /**
     * Возвращает наименование таблицы
     * 
     * @return string
     */
    public static function getTableName() 
    {
        return sprintf('b_iblock_element_prop_s%u', static::getIblockId());
    }
    
    /**
     * Возвращает описания полей таблицы
     * 
     * @return array
     */
    public static function getMap()
    {
        return array_merge(
            array(
                new Entity\IntegerField('IBLOCK_ELEMENT_ID', array('primary' => true))
            ),
            static::getPropertiesMap()
        );
    }
    
    /**
     * Создает набор полей по свойствам инфоблока
     * 
     * @return array
     */
    protected static function createPropertiesMap(): array
    {
        $model = static::getIblockModel();
        
        $entityProvider = static::getProvider();
        $singleProvider = new PropertyProvider\Single();
        $multiProvider = new PropertyProvider\Multiple($entityProvider->getElementPropertyMultipleEntityName());
        
        $properties = $model->getProperties();
        $result = array();
        foreach ($properties as $prop) {
            $fields = ($prop['MULTIPLE'] == 'Y')
                ? $multiProvider->getPropertyFields($prop)
                : $singleProvider->getPropertyFields($prop);
            $result = array_merge($result, $fields);
        }
        
        return array_filter($result);
    }
}