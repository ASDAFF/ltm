<?php

namespace Ltm\Domain\Util\IblockOrm\DataManager;

use Bitrix\Main\Entity;
use Ltm\Domain\Util\IblockOrm\AbstractIblockIdGetterTrait;

/**
 * Абстрактный класс для таблицы множественных свойств инфоблока 2.0
 * Должен определить метод, возващающий идентификатор инфоблока
 */
abstract class ElementPropertyMultiple extends Entity\DataManager 
{
    use AbstractIblockIdGetterTrait;
    
    /**
     * Возвращает наименование таблицы для хранения множественных значений
     * 
     * @return string
     */
    public static function getTableName() 
    {
        return sprintf('b_iblock_element_prop_m%u', static::getIblockId());
    }
    
    /**
     * Возвращает описание полей
     * 
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true
            ),
            'IBLOCK_ELEMENT_ID' => array(
                'data_type' => 'integer',
                'required' => true
            ),
            'IBLOCK_PROPERTY_ID' => array(
                'data_type' => 'integer',
                'required' => true
            ),
            'VALUE' => array(
                'data_type' => 'text',
                'required' => true
            ),
            'VALUE_ENUM' => array(
                'data_type' => 'integer'
            ),
            'VALUE_NUM' => array(
                'data_type' => 'float'
            ),
            'DESCRIPTION' => array(
                'data_type' => 'string'
            )
        );
    }
}

