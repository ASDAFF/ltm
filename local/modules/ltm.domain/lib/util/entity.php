<?php

namespace Ltm\Domain\Util;

use Bitrix\Main;

abstract class Entity
{
    protected $fields = array();
    
    /**
     * @param mixed $primary
     * @return self|null
     */
    public static function load($primary)
    {
        $row = static::getDataByPrimary($primary);
        return static::create($row);
    }
    
    /**
     * @param array|mixed $identifiers
     * @return array<self>
     * 
     * @uses self::getIdentifiersConditia()
     * @uses self::loadListByCondition()
     */
    public static function loadList(...$identifiers)
    {
        if(empty($identifiers)) {
            return array();
        }
        
        if(count($identifiers) === 1 && is_array($identifiers[0])) {
            $identifiers = $identifiers[0];
        }
        
        $conditia = static::getIdentifiersConditia($identifiers);
        return static::loadListByCondition($conditia);
    }
    
    /**
     * @param array $conditia
     * @param array $params
     * 
     * @return array<self>
     * 
     * @uses self::createCondition()
     * @uses self::getFetchConverter()
     * @uses self::create()
     */
    public static function loadListByCondition(array $conditia, array $params = array())
    {
        /** @var \Bitrix\Main\Entity\DataManager $className */
        $className = static::getEntityClass();
        
        $q = $className::getList(array_replace(
            $params, 
            static::createCondition($conditia, static::getFieldsSelection())
        ));
        
        $result = array();
        while($d = $q->fetch(static::getFetchConverter())) {
            $result[] = static::create($d);
        }
        
        return $result;
    }
    
    /**
     * @param array $condition
     * @return self|null
     */
    public static function loadByCodition(array $condition)
    {
        $row = static::getDataByCondition($condition);
        return static::create($row);
    }
    
    /**
     * @return string Table class name
     * @throws Main\NotImplementedException
     */
    public static function getEntityClass()
    {
        throw new Main\NotImplementedException();
    }
    
    /**
     * @return array
     */
    public static function getFieldsSelection()
    {
        return array('*');
    }
    
    /**
     * @param array $fields
     * @return self
     */
    protected static function create($fields)
    {
        if(!is_array($fields)) {
            return null;
        }
        
        return new static($fields);
    }
    
    /**
     * @param array $condition
     * @return array|false
     */
    protected static function getDataByCondition(array $condition)
    {
        /** @var \Bitrix\Main\Entity\DataManager $className */
        $className = static::getEntityClass();
        return $className::getRow(static::createCondition($condition, static::getFieldsSelection()));
    }
    
    /**
     * @param mixed $primary
     * @return array|false
     * 
     * @uses self::getEntityClass()
     * @uses \Bitrix\Main\Entity\DataManager::getByPrimary()
     * @uses self::getLoadByPrimaryParams()
     * @uses self::getFetchConverter()
     */
    protected static function getDataByPrimary($primary)
    {
        /** @var \Bitrix\Main\Entity\DataManager $className */
        $className = static::getEntityClass();
        $result = $className::getByPrimary($primary, static::getLoadByPrimaryParams());
        return $result->fetch(static::getFetchConverter());
    }

    /**
     * @return array
     * @uses self::getFieldsSelection()
     */
    protected static function getLoadByPrimaryParams()
    {
        return array_merge_recursive(array(
            'select' => static::getFieldsSelection(),
            'limit' => 1
        ), static::getDefaultSelectionParams());
    }
    
    /**
     * @param array $filter
     * @param array $select
     * @return array
     */
    protected static function createCondition(array $filter, array $select)
    {
        return array_merge_recursive(array(
            'filter' => $filter,
            'select' => $select
        ), static::getDefaultSelectionParams());
    }
    
    /**
     * @return \Bitrix\Main\Text\Converter|null
     */
    protected static function getFetchConverter()
    {
        return null;
    }

    /**
     * @param array $identifiers
     * @param string|null $entityClassName
     * @return array
     */
    protected static function getIdentifiersConditia(array $identifiers, string $entityClassName = null)
    {
        /** @var \Bitrix\Main\Entity\DataManager $entityClassName */
        if(empty($entityClassName)) {
            $entityClassName = static::getEntityClass();
        }

        $entity = $entityClassName::getEntity();
        $primary = $entity->getPrimary();
                
        return (
            (is_array($primary))
            ? static::formatMultiIndentifiersConditia($primary, $identifiers)
            : static::formatSingleIdentifiersConditia($primary, $identifiers)
        );
    }
    
    /**
     * @param array $primary
     * @param array $identifiers
     * @return array
     */
    protected static function formatMultiIndentifiersConditia(array $primary, array $identifiers)
    {
        $identKeys = array_combine(
            $primary, 
            array_map(function($a) {
                return sprintf('=%s', $a);
            }, $primary)
        );
            
        $conditia = array_map(function($cond) use($identKeys) {
            $values = array_intersect_key($cond, $identKeys);
            $keys = array_intersect_key($identKeys, $values);
            
            ksort($values);
            ksort($keys);
            
            return array_combine($keys, $values);
        }, $identifiers);
        
        return array(
            'LOGIC' => 'OR',
            $conditia
        );
    }
    
    /**
     * @param string $primary
     * @param array $identifiers
     * @return array
     */
    protected static function formatSingleIdentifiersConditia($primary, array $identifiers)
    {
        return array(
            sprintf('@%s', $primary) => $identifiers
        );
    }
    
    /**
     * @param array $fields
     */
    protected function __construct(array $fields) 
    {
        $this->fields = $fields;
    }
    
    /**
     * @return mixed
     * @uses self::getField()
     */
    public function getId()
    {
        return $this->getField('ID');
    }
    
    /**
     * Возвращает данные поля сущности по ключу
     * 
     * @param string $field
     * @return mixed
     */
    public function getField($field)
    {
        return $this->fields[$field];
    }
    
    /**
     * Возвращает данные всех полей сущности
     * 
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    static public function getDefaultSelectionParams()
    {
        return array();
    }
}