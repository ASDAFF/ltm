<?php

namespace Ltm\Domain\IblockOrm;

use Bitrix\Iblock;
use Bitrix\Main;

class Model
{
    const VERSION_PROPERTIES_COMMON = 1;
    
    const VERSION_PROPERTIES_SEPARATE = 2;
    
    /**
     * Массив данных инфоблока
     * @var array
     */
    protected $fields;
    
    /**
     * Массив свойств инфоблока
     * @var array
     */
    protected $properties;
    
    /**
     * Внутреннее хранилище идентификаторов инфоблоков по коду и типу
     * @var array
     */
    protected static $codeMap = array();
    
    /**
     * Внутреннее хранилище объектов моделей
     * @var array
     */
    protected static $storage = array();
    
    /**
     * Возвращает объект модели по идентификатору инфоблока
     * 
     * @param int $id
     * 
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
     * Возвращает объект модели по коду и типу инфоблока
     * 
     * @param string $code
     * @param string $type
     * 
     * @return self|false
     */
    public static function getByCode(string $code, string $type = null)
    {
        $id = static::getIdByCode($code, $type);
        if(!empty($id)) {
            return static::get($id);
        }
        
        return static::loadByCondition(static::getCodeCondition($code, $type));
    }
    
    /**
     * Возвращает идентификатор по коду и типу из внутреннего хранилища
     * 
     * @param string $code
     * @param string $type
     * 
     * @return int|null
     */
    protected static function getIdByCode(string $code, string $type = null)
    {
        $key = (
            !empty($type)
            ? static::formatCodeType($code, $type)
            : $code
        );
        
        return static::$codeMap[$key];
    }
    
    /**
     * Осуществляет выборку данных по массиву условий, создание объекта и сохранение
     * его во внутреннее хранилище
     * 
     * @param array $condition
     * @param \Bitrix\Main\Entity\Base $entity
     * @return self|boolean
     */
    protected static function loadByCondition(array $condition, Main\Entity\Base $entity = null)
    {
        $fields = static::getFieldsByCondition($condition, $entity);
        if(!$fields) {
            return false;
        }
        
        $ob = new static($fields);
        static::registerEntity($ob);
        
        return $ob;
    }
    
    /**
     * Осуществляет выборку данных по массиву условий
     * 
     * @param array $condition
     * @param \Bitrix\Main\Entity\Base $entity Сущность для осуществления выборки
     * 
     * @return array|false
     */
    protected static function getFieldsByCondition(array $condition, Main\Entity\Base $entity = null)
    {
        if(empty($entity)) {
            $entity = Iblock\IblockTable::getEntity();
        }
        
        return (new Main\Entity\Query($entity))
            ->setFilter($condition)
            ->setSelect(array('*', 'TYPE.ID'))
            ->exec()
            ->fetch();
    }
    
    /**
     * Возвращает набор условий выборки данных по идентификатору инфоблока
     * 
     * @param int $id
     * 
     * @return array
     */
    protected static function getIdCondition(int $id): array
    {
        return array('=ID' => $id);
    }
    
    /**
     * Возвращает набор условий для выборки по типу и коду инфоблока
     * 
     * @param string $code
     * @param string|null $type
     * 
     * @return array
     */
    protected static function getCodeCondition($code, $type): array
    {
        $result = array('=CODE' => $code);
        if(!empty($type)) {
            $result['=TYPE.ID'] = $type;
        }
        
        return $result;
    }
    
    /**
     * Сохраняет объект модели во внутреннем хранилище
     * 
     * @param self $ob
     */
    protected static function registerEntity(self $ob)
    {
        static::$storage[$ob->getId()] = $ob;
        static::$codeMap[$ob->getCode()] = $ob->getId();
        static::$codeMap[static::formatCodeType($ob->getCode(), $ob->getType())] = $ob->getId();
    }

    /**
     * Возвращает внутренний ключ идентификатора и типа для хранилища идентификаторов
     * 
     * @param string $code
     * @param string $type
     * @return string
     */
    protected static function formatCodeType(string $code, string $type) : string
    {
        return sprintf('%s:%s', $code, $type);
    }


    /**
     * @param array $fields
     */
    protected function __construct(array $fields) 
    {
        $this->fields = $fields;
    }
    
    /**
     * Возвращает идентификатор инфоблока
     * 
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->getField('ID');
    }
    
    /**
     * Возвращает код инфоблока
     * 
     * @return string
     */
    public function getCode(): string
    {
        return (string)$this->getField('CODE');
    }
    
    /**
     * Возвращает идентификатор типа инфоблока
     * 
     * @return string
     */
    public function getType(): string
    {
        return (string)$this->getField('TYPE_ID');
    }
    
    /**
     * Возвращает версию инфоблока
     * 
     * @return int
     */
    public function getVersion(): int
    {
        return (int)$this->getField('VERSION');
    }
    
    /**
     * Возвращает данные конкретного поля
     * 
     * @param string $key
     * @return mixed
     */
    public function getField(string $key)
    {
        return $this->fields[$key];
    }
    
    /**
     * Возвращает данные всех полей инфоблока в виде ассоциативного массива
     * 
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
    
    /**
     * Возвращает массив свойств инфоблока
     * 
     * @param bool $force
     * @return array
     */
    public function getProperties(bool $force=false): array
    {
        if(!empty($this->properties) && !$force) {
            return $this->properties;
        }
        
        $q = Iblock\PropertyTable::getList(array(
            'filter' => array(
                '=IBLOCK_ID' => $this->getId()
            ),
            'order' => array(
                'SORT' => 'ASC',
                'CODE' => 'ASC',
                'ID' => 'ASC'
            )
        ));
        
        $res = array();
        while($d = $q->fetch()) {
            $res[] = $d;
        }
        
        return $this->properties = $res;
    }
    
    /**
     * @param string $code
     * @return array|null
     */
    public function getPropertyByCode($code)
    {
        $prop = $this->getProperties();
        $idx = array_search($code, array_column($prop, 'CODE'));
        if($idx === false) {
            return null;
        }
        
        return $prop[$idx];
    }
}