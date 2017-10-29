<?php

namespace Ltm\Domain\IblockOrm\DataManager;

use Bitrix\Iblock;
use Bitrix\Main\Entity;
use Ltm\Domain\IblockOrm\EntityProviderGetterTrait;

/**
 * Абстрактный табличный класс для элемента, определяет поля-связки с сущностью
 * свойств и секций
 */
abstract class Element extends Iblock\ElementTable 
{
    use EntityProviderGetterTrait;
   
    public static function getMap()
    {
        $provider = static::getProvider();
        
        return array_merge(
            parent::getMap(),
            array(
                static::createPropertyReferenceField($provider->getElementPropertyEntityName())
            )
        );
    }
    
    public static function createPropertyReferenceField($entity, $name = 'PROPERTY', $joinType = 'left')
    {
        return static::createReferenceField(
            $entity,
            $name,
            array('=this.ID' => 'ref.IBLOCK_ELEMENT_ID'),
            $joinType
        );
    }
    
    public static function createReferenceField($entity, $name, $condition, $joinType = 'left')
    {
        return new Entity\ReferenceField(
            $name,
            $entity,
            $condition,
            array('join_type' => $joinType)
        );
    }
}