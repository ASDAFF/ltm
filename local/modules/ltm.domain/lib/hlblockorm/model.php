<?php

namespace Ltm\Domain\HlblockOrm;

use Bitrix\Highloadblock;
use Bitrix\Main;

/**
 * Класс для получения данных hl-блоков
 */
class Model
{
    /**
     * @var array<int,self>
     */
    protected static $storage = array();
    
    /**
     * @var array<string,int> 
     */
    protected static $entityMap = array();
    
    /**
     * @var array<string,int> 
     */
    protected static $tableMap = array();
    
    /**
     * @var array
     */
    protected $fields;
    
    /**
     * @var array
     */
    protected $entityFields;
    
    /**
     * Возвращает объект модели по идентификатору сущности
     * 
     * @param string $entityName
     * @return self|false
     */
    public static function getByEntity(string $entityName)
    {
        $id = static::getIdByEntity($entityName);
        if(!empty($id)) {
            return static::get((int)$id);
        }
        
        return static::loadByCondition(static::getEnityCondition($entityName));
    }
    
    /**
     * Возвращает объект модели по названию таблицы
     * 
     * @param string $tableName
     * @return self|false
     */
    public static function getByTable(string $tableName)
    {
        $id = static::getIdByTable($tableName);
        if(!empty($id)) {
            return static::get((int)$id);
        }
        
        return static::loadByCondition(static::getTableCondition($tableName));
    }
    
    /**
     * Возвращает объект модели по идентификатору hl-блока
     * 
     * @param int $id
     * @return self|false
     */
    public static function get(int $id)
    {
        if(!empty(static::$storage[$id])) {
            return static::$storage[$id];
        }
        
        return static::loadByCondition(static::getIdCondition($id));
    }
    
    /**
     * Осуществляет подгрузку данных из указанной сущности по набору условий, 
     * возвращает объект модели
     * 
     * @param array $condition
     * @param \Bitrix\Main\Entity\Base $entity
     * @return self|false
     */
    protected static function loadByCondition(array $condition, Main\Entity\Base $entity = null)
    {
        $fields = static::getFieldsByCondition($condition, $entity);
        if(!$fields) {
            return false;
        }
        
        return static::createByFields($fields);
    }
    
    /**
     * Осуществляет получение данных из указанной сущности по набору условий
     * 
     * @param array $condition
     * @param \Bitrix\Main\Entity\Base $entity
     * @return array|null
     */
    protected static function getFieldsByCondition(array $condition, Main\Entity\Base $entity = null)
    {
        if(empty($entity)) {
            $entity = Highloadblock\HighloadBlockTable::getEntity();
        }
        
        $q = static::getQuery($entity)
            ->setFilter($condition)
            ->exec();
        
        return $q->fetch();
    }
    
    /**
     * Возвращает объект запроса к указанной сущности с предустановленными настройками
     * 
     * @param \Bitrix\Main\Entity\Base $entity
     * @return \Bitrix\Main\Entity\Query
     */
    protected static function getQuery(Main\Entity\Base $entity): Main\Entity\Query
    {
        return (new Main\Entity\Query($entity))
            ->registerRuntimeField(new Main\Entity\ExpressionField(
                'LANG_NAME',
                'CONCAT("{", GROUP_CONCAT(DISTINCT CONCAT(\'"\', QUOTE(IFNULL(%s, "")), "\":\"", QUOTE(IFNULL(%s, ""))) SEPARATOR ","), "}")',
                array(
                    'LANG.LID', 'LANG.NAME'
                ),
                array(
                    'fetch_data_modification' => function() {
                        return array(
                            function($val) {
                                return json_decode($val, true);
                            }
                        );
                    }
                )
            ))
            ->setGroup(array('ID'))
            ->setSelect(array('ID', 'NAME', 'TABLE_NAME', 'LANG_NAME'));
    }
    
    /**
     * Создает объект модели по набору данных
     * 
     * @param array $fields
     * @return self
     */
    protected static function createByFields(array $fields): self
    {
        $ent = new static($fields);
        static::registerModel($ent);
        
        return $ent;
    }

    /**
     * Возвращает идентификатор по наименованию сущности
     * 
     * @param string $entityName
     * @return int|null
     */
    protected static function getIdByEntity(string $entityName)
    {
        return static::$entityMap[$entityName];
    }
    
    /**
     * Возвращает идентификатор по наименованию таблицы
     * 
     * @param string $tableName
     * @return int|null
     */
    protected static function getIdByTable(string $tableName)
    {
        return static::$tableMap[$tableName];
    }
    
    /**
     * Регистрирует объект модели во внутреннем хранилище
     * 
     * @param self $model
     */
    protected static function registerModel(self $model)
    {
        $id = $model->getId();
        static::$storage[$id] = $model;
        static::$entityMap[$model->getEntity()] = $id;
        static::$tableMap[$model->getTable()] = $id;
    }
    
    /**
     * Возвращает набор условий для выборки по идентификатору
     * 
     * @param int $id
     * @return array
     */
    protected static function getIdCondition(int $id): array
    {
        return array('=ID' => $id);
    }
    
    /**
     * Возвращает набор условий для выборки по именованию сущности
     * 
     * @param string $entityName
     * @return array
     */
    protected static function getEnityCondition(string $entityName): array
    {
        return array('=NAME' => $entityName);
    }
    
    /**
     * Возвращает набор условий для выборки по наименованию таблицы
     * 
     * @param string $tableName
     * @return array
     */
    protected static function getTableCondition(string $tableName): array
    {
        return array('=TABLE_NAME' => $tableName);
    }
    
    protected static function loadEntityFields($ufEntity)
    {
        global $USER_FIELD_MANAGER;
        return $USER_FIELD_MANAGER->getUserFields($ufEntity);
    }
    
    /**
     * @param array $fields
     */
    protected function __construct(array $fields)
    {
        $this->fields = $fields;
    }
    
    /**
     * Возвращает именование сущности
     * 
     * @return string
     */
    public function getEntity()
    {
        return $this->getField('NAME');
    }
    
    /**
     * Возвращает идентификатор
     * 
     * @return int
     */
    public function getId()
    {
        return $this->getField('ID');
    }
    
    /**
     * Возвращает наименование таблицы
     * 
     * @return string
     */
    public function getTable()
    {
        return $this->getField('TABLE_NAME');
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
    
    /**
     * Возвращает именование hl-блока для переданного языка, если идентификатор
     * языка не передан — используется текущий язык сайта.
     * 
     * @param string|null $lang
     * @return string|null
     */
    public function getName($lang = null)
    {
        if(empty($lang)) {
            $lang = LANGUAGE_ID;
        }
        if(!isset($this->fields['LANG_NAME'][$lang])) {
            $lang = array_keys($this->fields['LANG_NAME'])[0];
        }
        
        return $this->fields['LANG_NAME'][$lang];
    }
    
    /**
     * @return string
     */
    public function getUfEntity()
    {
        return sprintf('HLBLOCK_%u', $this->getId());
    }
    
    /**
     * @param bool $force
     * @return array
     */
    public function getEntityFields($force = false)
    {
        if(empty($this->entityFields) || $force) {
            $this->entityFields = static::loadEntityFields($this->getUfEntity());
        }
        
        return $this->entityFields;
    }
}