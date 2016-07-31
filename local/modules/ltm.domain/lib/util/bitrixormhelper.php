<?php

namespace Ltm\Domain\Util;

use Bitrix\Main\DB;
use Bitrix\Main\Entity;
use Bitrix\Iblock;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;

class BitrixOrmHelper
{
    const REF_ELEMENT_PROPERTY = 1;
    
    const REF_ELEMENT_UF_SECTION = 2;
    
    const REF_ELEMENT_SECTIONS = 4;
    
    const REF_ELEMENT_UF_SECTIONS = 6;
    
    protected static $ufEntity = array();
    
    /**
     * @static
     * 
     * @param \Bitrix\Main\Entity\Base|string $entity
     * @param string $ufID
     * 
     * @return \Bitrix\Main\Entity\Base Entity with attached user fields
     */
    public static function attachEntityUserFields($entity, $ufID)
    {
        if($entity instanceof Entity\Base) {
            $entity = $entity->getDataClass();
        }
        
        $className = static::getUFEntityClassName($entity, $ufID);
        if(!class_exists($className, false)) {
            static::compileUfEntity($className, $entity, $ufID);
        }
        
        return $className::getEntity();
    }
    
    /**
     * @static
     * 
     * @param string $dbTableName
     * @param array $arFieldMap
     * @param string|null $connectionName
     * @param bool $uts
     * @param bool $utm
     * 
     * @return Ltm\Domain\Util\DynamicBase
     */
    public static function createDynamicBase($dbTableName, array $arFieldMap = array(), $connectionName = null, $uts = false, $utm = false)
    {
        return new DynamicBase($dbTableName, $arFieldMap, $connectionName, $uts, $utm);
    }
    
    /**
     * @param int $iblockID
     * @param int $refMask
     * 
     * @return \Bitrix\Main\Entity\Query
     */
    public static function getIBlockElementQuery($iblockID, $refMask = 0)
    {
        $q = Iblock\ElementTable::query();
        $q->addFilter('=IBLOCK_ID', $iblockID);
        
        if($refMask & static::REF_ELEMENT_PROPERTY) {
            $propEnt = static::getIBlockPropertiesEntity($iblockID);
            $q->registerRuntimeField('PROPERTY', array(
                'data_type' => $propEnt,
                'reference' => array(
                    '=this.ID' => 'ref.IBLOCK_ELEMENT_ID'
                )
            ));
        }
        
        if($refMask & static::REF_ELEMENT_SECTIONS) {
            $q->registerRuntimeField('SECTIONS', array(
                'data_type' => 'Bitrix\Iblock\SectionElement',
                'reference' => array(
                    '=this.ID' => 'ref.IBLOCK_ELEMENT_ID'
                )
            ));
        }
        
        if($refMask & static::REF_ELEMENT_UF_SECTION) {
            $sectionEnt = static::attachEntityUserFields(
                Iblock\SectionTable::getEntity(),
                static::getIBlockSectionUserFieldID($iblockID)
            );
            
            if($refMask & static::REF_ELEMENT_SECTIONS) {
                $q->registerRuntimeField('SECTION_REF', array(
                    'data_type' => $sectionEnt,
                    'reference' => array(
                        '=this.SECTIONS.IBLOCK_SECTION_ID' => 'ref.ID'
                    )
                ));
            } else {
                $q->registerRuntimeField('SECTION_REF', array(
                    'data_type' => $sectionEnt,
                    'reference' => array(
                        '=this.IBLOCK_SECTION_ID' => 'ref.ID'
                    )
                ));
            }
        }
        
        return $q;
    }
    
    /**
     * @param int $iblockId
     * @return \Bitrix\Main\Entity\Base
     */
    public static function getIBlockPropertiesEntity($iblockId)
    {
        $props = static::getIblockProperties(array(
            '=IBLOCK_ID' => $iblockId
        ));
        
        if(empty($props)) {
            return null;
        }
        
        return static::getEntityByProp($iblockId, $props);
    }
    
    /**
     * @param string $iblockCode
     * @param string $iblockTypeId
     * @return \Bitrix\Main\Entity\Base
     */
    public static function getIBlockPropertiesEntityByCode($iblockCode, $iblockTypeId = '')
    {
        $filter = array(
            '=IBLOCK.CODE' => $iblockCode
        );
        if(!empty($iblockTypeId)) {
            $filter['=IBLOCK.IBLOCK_TYPE_ID'] = $iblockTypeId;
        }
        $props = static::getIblockProperties($filter);
        
        if(empty($props)) {
            return null;
        }
        
        return static::getEntityByProp($props[0]['IBLOCK_ID'], $props);
    }
    
    /**
     * @param int $iblockID
     * @return string
     */
    public static function getIBlockSectionUserFieldID($iblockID)
    {
        return sprintf('IBLOCK_%u_SECTION', $iblockID);
    }


    /**
     * @static
     * 
     * @param int $iblockId
     * @return string
     */
    public static function getIBlockMultiplePropTableName($iblockId)
    {
        return sprintf('b_iblock_element_prop_m%u', $iblockId);
    }
    
    /**
     * @static
     * 
     * @param int $iblockId
     * @return string
     */
    public static function getIBlockSinglePropTableName($iblockId)
    {
        return sprintf('b_iblock_element_prop_s%u', $iblockId);
    }
    
    /**
     * @static
     * @return array
     */
    public static function getMultiplePropertyTableMap()
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
    
    /**
     * @param string $code
     * @param string $type
     * @return int|null
     */
    public static function getIblockIdByCode($code, $type = '')
    {
        $filter = array(
            '=CODE' => $code
        );
        if(!empty($type)) {
            $filter['=IBLOCK_TYPE_ID'] = $type;
        }
        
        $result = IblockTable::getRow(array(
            'filter' => $filter,
            'select' => array('ID')
        ));
        
        if(!empty($result) && $result['ID']) {
            return $result['ID'];
        }
        
        return null;
    }
    
    /**
     * @param int $iblockID
     * @return Bitrix\Main\Entity\Base
     */
    public static function getIBlockSectionEntity($iblockID)
    {
        return static::attachEntityUserFields(
            Iblock\SectionTable::getEntity(),
            static::getIBlockSectionUserFieldID($iblockID)
        );
    }

    /**
     * @param \Bitrix\Main\Entity\Query $query
     * @return int
     */
    public static function getCountByQuery(Entity\Query $query)
    {
        $q = clone $query;
        $q->setOrder(array())
            ->setOffset(0)
            ->setLimit(0);
        
        $sql = sprintf(
            'SELECT COUNT(*) FROM (%s) AS A',
            $q->getQuery()
        );
        
        $result = $q->getEntity()->getConnection()->queryScalar($sql);
        return (!!$result) ? intval($result) : 0;
    }
    
    /**
     * @param string $fieldName
     * @param string $refName
     * @return \Bitrix\Main\Entity\ReferenceField
     */
    public static function getFileReferenceField($fieldName, $refName)
    {
        return new Entity\ReferenceField(
            $fieldName,
            '\Bitrix\Main\File',
            array('=this.'.$refName => 'ref.ID'),
            array('join_type' => 'LEFT')
        );
    }
    
    /**
     * @param \Bitrix\Main\Entity\Query $query
     * @param string $fieldName
     * @param string $refName
     * @return \Bitrix\Main\Entity\Query $query
     */
    public static function attachFileReferenceField($query, $fieldName, $refName)
    {
        $query->registerRuntimeField('', static::getFileReferenceField($fieldName, $refName) );
        return $query;
    }

    /**
     * @param string $fieldName
     * @param string $refName
     * @return \Bitrix\Main\Entity\ExpressionField
     */
    public static function getFilePathExpressionField($fieldName, $refName, $delimiter = '.')
    {
        return new Entity\ExpressionField(
            $fieldName,
            "CONCAT(%s,'***',%s)",
            [$refName.$delimiter."SUBDIR", $refName.$delimiter."FILE_NAME"],
            [ 'fetch_data_modification' => function () {
                return [function ($value) {
                    $fileArr = explode("***", $value);
                    $results["SUBDIR"] = $fileArr[0];
                    $results["FILE_NAME"] = $fileArr[1];
                    $resFile = '';
                    if(!empty($fileArr[0])) {
                        $resFile = \Cfile::GetFileSRC($results, false, false);
                    }
                    return $resFile;
                }];
            }]
        );
    }
    
    /**
     * @param \Bitrix\Main\Entity\Query $query
     * @param string $fieldName
     * @param string $refName
     * @return \Bitrix\Main\Entity\Query $query
     */
    public static function attachFilePathExpressionField($query, $fieldName, $refName)
    {
        $query->registerRuntimeField('', static::getFilePathExpressionField($fieldName, $refName) );
        return $query;
    }
    
    /**
     * @param \Bitrix\Main\Entity\Query $query
     * @param string $fieldName
     * @param string $refName
     * @return \Bitrix\Main\Entity\Query $query
     */
    public static function attachFileFields($query, $fieldName, $refFieldName, $refName = '')
    {
        if(empty($refName)) {
            $refName = $refFieldName.'_PATH';
        }
        static::attachFileReferenceField($query, $refName, $refFieldName);
        static::attachFilePathExpressionField($query, $fieldName, $refName);
        return $query;
    }

    /**
     * @param array $filter
     * @param array $select If not set it returns all entity fields
     * @return array
     */
    protected static function getIblockProperties(array $filter = array(), array $select = array())
    {
        $q = PropertyTable::query()
            ->setFilter(array_merge(array('ACTIVE' => true), $filter))
            ->setSelect(!empty($select) ? $select : array('*'))
            ->exec();
        
        /* array(
                'ID', 'IBLOCK_ID', 'CODE', 'MULTIPLE', 'PROPERTY_TYPE', 
                'LIST_TYPE', 'WITH_DESCRIPTION', 'USER_TYPE',
                'USER_TYPE_SETTINGS'
            ) */
        
        $result = array();
        while($d = $q->fetch()) {
            $result[] = $d;
        }
        
        return $result;
    }
    
    /**
     * @param int $iblockId
     * @param array $properties
     * @return \Bitrix\Main\Entity\Base
     */
    protected static function getEntityByProp($iblockId, array $properties)
    {
        /**
         * @type \Bitrix\Main\Entity\Base
         */
        $entity = static::createDynamicBase(
            static::getIBlockSinglePropTableName($iblockId), 
            array(
                'IBLOCK_ELEMENT_ID' => array(
                    'data_type' => 'integer',
                    'primary' => true
                )
            )
        );
        
        $multipleRef = null;
        foreach($properties as $prop) {
            if($prop['MULTIPLE'] == 'Y') {
                if(empty($multipleRef)) {
                    $multipleRef = static::createDynamicBase(
                        static::getIBlockMultiplePropTableName($iblockId),
                        static::getMultiplePropertyTableMap()
                    );
                }
                static::addMultipleProperty($entity, $multipleRef, $prop);
            } else {
                static::addSingleProperty($entity, $prop);
            }
        }
        
        return $entity;
    }
    
    protected static function getEntityFieldAttrsByProperty(array $prop)
    {
        $result = array();
        
        switch($prop['PROPERTY_TYPE']) {
            case PropertyTable::TYPE_ELEMENT:
            case PropertyTable::TYPE_FILE:
            case PropertyTable::TYPE_LIST:
            case PropertyTable::TYPE_SECTION:
                $result['data_type'] = 'integer';
                break;
            case PropertyTable::TYPE_NUMBER:
                $result['data_type'] = 'float';
                break;
            case PropertyTable::TYPE_STRING:
                if($prop['USER_TYPE'] == 'HTML') {
                    $result['data_type'] = 'text';
                    $result['serialized'] = true;
                } else {
                    $result['data_type'] = 'string';
                }
                break;
        }
        
        return $result;
    }
    
    protected static function getEntityRefFieldByProperty(array $prop, $colname = '')
    {
        $result = array();
        
        if(empty($colname)) {
            $colname = $prop['CODE'];
        }
        
        switch($prop['PROPERTY_TYPE']) {
            case PropertyTable::TYPE_ELEMENT:
                $result['data_type'] = '\Bitrix\Iblock\Element';
                $result['reference'] = array(
                    sprintf('=this.%s', $colname) => 'ref.ID'
                );
                break;
            case PropertyTable::TYPE_FILE:
                $result['data_type'] = '\Bitrix\Main\File';
                $result['reference'] = array(
                    sprintf('=this.%s', $colname) => 'ref.ID'
                );
                break;
            case PropertyTable::TYPE_LIST:
                $result['data_type'] = '\Bitrix\Iblock\PropertyEnumeration';
                $result['reference'] = array(
                    sprintf('=this.%s', $colname) => 'ref.ID'
                );
                break;
            case PropertyTable::TYPE_SECTION:
                $result['data_type'] = '\Bitrix\Iblock\Section';
                $result['reference'] = array(
                    sprintf('=this.%s', $colname) => 'ref.ID'
                );
                break;
            case PropertyTable::TYPE_STRING:
                if($prop['USER_TYPE'] == 'UserID') {
                    $result['data_type'] = '\Bitrix\Main\User';
                    $result['reference'] = array(
                        sprintf('=this.%s', $colname) => 'ref.ID'
                    );
                } elseif($prop['USER_TYPE'] == 'ElementXmlID') {
                    $result['data_type'] = '\Bitrix\Iblock\Element';
                    $result['reference'] = array(
                        sprintf('=this.%s', $colname) => 'ref.XML_ID'
                    );
                } elseif($prop['USER_TYPE'] == 'directory') {
                    $curSettings = unserialize($prop['USER_TYPE_SETTINGS']);
                    $hlBlockId = HLBlockHelper::getIdByBase( $curSettings["TABLE_NAME"] );
                    $result['data_type'] = HLBlockHelper::getEntityById($hlBlockId);
                    $result['reference'] = array(
                        sprintf('=this.%s', $colname) => 'ref.UF_XML_ID',
                    );
                }
                break;
        }
        
        return $result;
    }
    
    protected static function addSingleProperty(Entity\Base $entity, $prop)
    {
        /**
         * @type string
         */
        $fieldName = $prop['CODE'];
        /**
         * @type string
         */
        $colname = static::getSinglePropertyColname($prop['ID']);
        
        /**
         * @type array
         */
        $attrs = static::getEntityFieldAttrsByProperty($prop);
        $attrs['column_name'] = $colname;
        $entity->addField($attrs, $fieldName);
        
        $ref = static::getEntityRefFieldByProperty($prop);
        if(!empty($ref)) {
            $entity->addField($ref, sprintf('%s_VALUE', $fieldName));
        }
        
        if($prop['WITH_DESCRIPTION'] == 'Y') {
            $entity->addField(
                array(
                    'data_type' => 'string',
                    'column_name' => static::getSingleDescriptionColname($prop['ID'])
                ),
                sprintf('%s_DESCRIPTION', $fieldName)
            );
        }
    }
    
    protected static function addMultipleProperty(Entity\Base $entity, Entity\Base $ref, $prop)
    {
        /**
         * @type string
         */
        $fieldName = $prop['CODE'];
        
        /**
         * @type string
         */
        $colName = static::getMultiplePropertyColname($prop);
        
        /**
         * @type string
         */
        $refFieldName = sprintf('%s_REF', $prop['CODE']);
        
        $entity->addField(array(
            'data_type' => $ref,
            'reference' => array(
                '=this.IBLOCK_ELEMENT_ID' => 'ref.IBLOCK_ELEMENT_ID',
                '=ref.IBLOCK_PROPERTY_ID' => new DB\SqlExpression('?i', $prop['ID'])
            )
        ), $refFieldName);
        
        $entity->addField(array(
            'expression' => array(
                '%s', sprintf('%s.%s', $refFieldName, $colName)
            )
        ), $fieldName);
        
        $entity->addField(
            array(
                'expression' => array(
                    '%s', sprintf('%s.ID', $refFieldName)
                )
            ),
            sprintf(
                '%s_VALUE_ID', $fieldName
            )
        );
        
        if($colName != 'VALUE') {
            $entity->addField(
                array(
                    'expression' => array(
                        '%s', sprintf('%s.VALUE', $refFieldName)
                    )
                ),
                sprintf(
                    '%s_VALUE', $fieldName
                )
            );
        }
        
        if($prop['WITH_DESCRIPTION'] == 'Y') {
            $entity->addField(
                array(
                    'expression' => array(
                        '%s', sprintf('%s.DESCRIPTION', $refFieldName)
                    )
                ),
                sprintf(
                    '%s_DESCRIPTION', $fieldName
                )
            );
        }
        
        $rref = static::getEntityRefFieldByProperty($prop, 
            sprintf('%s.%s', $refFieldName, $colName));
        if(!empty($rref)) {
            $entity->addField($rref, sprintf('%s_VALUE', $fieldName));
        }
    }

    protected static function getSinglePropertyColname($propId)
    {
        return sprintf('PROPERTY_%u', $propId);
    }
    
    protected static function getSingleDescriptionColname($propId)
    {
        return sprintf('DESCRIPTION_%u', $propId);
    }
    
    protected static function getMultiplePropertyColname($prop)
    {
        switch($prop['PROPERTY_TYPE']) {
            case PropertyTable::TYPE_ELEMENT:
            case PropertyTable::TYPE_NUMBER:
            case PropertyTable::TYPE_SECTION:
                return 'VALUE_NUM';
                
            case PropertyTable::TYPE_LIST:
                return 'VALUE_ENUM';
                
            default:
                return 'VALUE';
        }
    }
    
    /**
     * @deprecated
     */
    protected static function getUFEntityKey(Entity\Base $entity, $ufID)
    {
        return sprintf('%s:%s', $entity->getFullName(), $ufID);
    }
    
    /**
     * @param string $dataClassName
     * @param string $ufID
     * @return string
     */
    protected static function getUFEntityClassName($dataClassName, $ufID)
    {
        $baseClass = preg_replace('@^(.+)Table$@', '$1', 
            basename(str_replace('\\', '/', $dataClassName)));
        
        $ufCamel = strtr(ucwords(str_replace('_', ' ', strtolower($ufID))), array(' ' => ''));
        
        return sprintf('%sWithUf%sTable', $baseClass, $ufCamel);
    }
    
    /**
     * Генерирует код класса `$className`, отнаследованного от `$baseClass` 
     * с объявлением метода `getUfId()`, возвращающем значение `$ufID`, 
     * и объявляет этот класс при помощи `eval()`
     * 
     * @param string $className
     * @param string $baseClass
     * @param string $ufID
     * 
     * @uses eval()
     */
    protected static function compileUfEntity($className, $baseClass, $ufID)
    {
        $export = sprintf(
            (
                'class %s extends %s {' . PHP_EOL
                . 'public static function getUfId() {' . PHP_EOL
                . 'return %s;' . PHP_EOL
                . '}' . PHP_EOL
                . '}'
            ),
            $className,
            $baseClass,
            var_export($ufID, true)
        );
        
        eval($export);
    }
}
