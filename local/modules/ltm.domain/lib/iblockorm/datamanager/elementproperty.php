<?php

namespace Ltm\Domain\IblockOrm\DataManager;

use Bitrix\Main\Entity;

/**
 * Табличный класс для общего хранилища значений свойств (инфоблоки 1.0)
 */
class ElementPropertyTable extends Entity\DataManager 
{
    /** @inheritDoc */
    public static function getTableName() 
    {
        return 'b_iblock_element_property';
    }
    
    /** @inheritDoc */
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