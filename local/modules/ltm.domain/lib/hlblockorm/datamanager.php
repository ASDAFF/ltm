<?php

namespace Ltm\Domain\Util\HlblockOrm;

use Bitrix\Highloadblock;
use Bitrix\Main\Entity;

abstract class DataManager extends Highloadblock\DataManager
{
    abstract public static function getEntityName(): string;
    
    /**
     * @inheritDoc
     */
    public static function getTableName() 
    {
        static::getModel()->getTable();
    }
    
    /**
     * @inheritDoc
     */
    public static function getHighloadBlock() 
    {
        static::getModel()->getFields();
    }
    
    public static function getMap()
    {
        return array_merge(
            array(
                'ID' => array(
                    'data_type' => 'integer',
                    'primary' => true,
                    'autocomplete' => true
                )
            )
        );
    }
    
    public static function getEntityProvider()
    {
        static::getManager()->getProvider(
            static::getEntityName(), 
            static::getEntityProviderCreator()
        );
    }
    
    public static function getManager(): Manager
    {
        return Manager::getInstance();
    }
    
    public static function getEntityProviderCreator()
    {
        return null;
    }
    
    /**
     * @return Model
     */
    public static function getModel()
    {
        return Model::getByEntity(static::getEntityName());
    }
}