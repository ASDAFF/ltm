<?php

namespace Ltm\Domain\Util\HlblockOrm\EntityProvider;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Entity;
use Ltm\Domain\Util\HlblockOrm\Model;

class DefaultProvider
{
    /**
     * @var Entity\DataManager
     */
    protected $className;
    
    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var Model 
     */
    protected $model;
    
    /**
     * @param string $entityName
     */
    public function __construct($entityName) 
    {
        $this->entityName = $entityName;
    }
    
    /**
     * @return Model
     */
    public function getModel()
    {
        if(empty($this->model)) {
            $this->model = Model::getByEntity($this->entityName);
        }
        
        return $this->model;
    }
    
    /**
     * @return string
     */
    public function getEntityName()
    {
        return sprintf('\%s', $this->entityName);
    }
    
    /**
     * @return Entity\DataManager
     */
    public function getEntityClassName()
    {
        if(empty($this->className)) {
            $this->className = $this->compileEntityClass();
        }
        
        return $this->className;
    }
    
    /**
     * @return boolean
     */
    public function isNative()
    {
        return true;
    }
    
    /**
     * @return string
     */
    protected function compileEntityClass()
    {
        $ent = HighloadBlockTable::compileEntity($this->getModel()->getFields());
        return $ent->getDataClass();
    }
}