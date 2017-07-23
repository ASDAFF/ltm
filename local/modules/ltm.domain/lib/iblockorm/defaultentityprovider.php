<?php

namespace Ltm\Domain\Util\IblockOrm;

use Bitrix\Main;

class DefaultEntityProvider implements EntityProviderInterface
{
    /**
     * @var int
     */
    protected $iblockId;
    
    /**
     * @var string 
     */
    protected $namespace;
    
    /**
     * @var string 
     */
    protected $elementEntity;
    
    /**
     * @var string 
     */
    protected $sectionEntity;
    
    /**
     * @var string 
     */
    protected $propertySingleEntity;
    
    /**
     * @var string 
     */
    protected $propertyMultipleEntity;
    
    /**
     * @param int $iblockId
     * @param string $namespace
     */
    public function __construct(int $iblockId, string $namespace = '')
    {
        $this->iblockId = $iblockId;
        $this->namespace = $namespace;
    }
    
    /** @inheritDoc */
    public function getElementTableClassName(): string
    {
        return static::getEntityTableClassName($this->getElementEntityName());
    }
    
    /** @inheritDoc */
    public function getElementEntityName(): string 
    {
        if(!isset($this->elementEntity)) {
            $this->elementEntity = $this->compileElementEntity();
        }
        
        return $this->elementEntity;
    }
    
    /**
     * Создает сущность элемента инфоблока
     * 
     * @return string
     */
    protected function compileElementEntity(): string
    {
        return $this->createEntity('Element', DataManager\Element::class);
    }
    
    /** @inheritDoc */
    public function getSectionTableClassName(): string
    {
        return static::getEntityTableClassName($this->getSectionEntityName());
    }
    
    /** @inheritDoc */
    public function getSectionEntityName(): string
    {
        if(!isset($this->sectionEntity)) {
            $this->sectionEntity = $this->compileSectionEntity();
        }
        
        return $this->sectionEntity;
    }
    
    /**
     * Создает сущность секций определенного инфоблока
     * 
     * @return string
     */
    protected function compileSectionEntity(): string
    {
        return $this->createEntity('Section', DataManager\Section::class);
    }
    
    /** @inheritDoc */
    public function getElementPropertyTableClassName(): string
    {
        return static::getEntityTableClassName($this->getElementPropertyEntityName());
    }
    
    /** @inheritDoc */
    public function getElementPropertyEntityName(): string
    {
        if(!isset($this->propertySingleEntity)) {
            $this->propertySingleEntity = $this->compilePropertySingleEntity();
        }
        
        return $this->propertySingleEntity;
    }
    
    /**
     * Создает сущность хранилища значений свойств в зависимости от версии инфоблока
     * 
     * @return string
     * 
     * @uses self::getModel()
     * @uses Model::getVersion()
     * @uses self::createEntity()
     */
    protected function compilePropertySingleEntity(): string
    {
        $model = $this->getModel();
        return $this->createEntity('Property', (
            $model->getVersion() === Model::VERSION_PROPERTIES_COMMON
            ? DataManager\ElementPropertyCommon::class
            : DataManager\ElementPropertySingle::class
        ));
    }
    
    /** @inheritDoc */
    public function getElementPropertyMultipleTableClassName(): string
    {
        return static::getEntityTableClassName($this->getElementPropertyMultipleEntityName());
    }
    
    /**
     * @inheritDoc
     * 
     * @uses self::compilePropertyMultipleEntity()
     */
    public function getElementPropertyMultipleEntityName(): string
    {
        if(!isset($this->propertyMultipleEntity)) {
            $this->propertyMultipleEntity = $this->compilePropertyMultipleEntity();
        }
        
        return $this->propertyMultipleEntity;
    }
    
    /**
     * Создает сущность хранилища значений множественных свойств
     * 
     * @return string
     * 
     * @uses self::createEntity()
     */
    protected function compilePropertyMultipleEntity(): string
    {
        return $this->createEntity('PropertyMultiple', DataManager\ElementPropertyMultiple::class);
    }

    /**
     * Создает класс определенной сущности, если такой класс еще не существует,
     * возвращает полное имя сущности.
     * 
     * @param string $entity
     * @param string $parent
     * 
     * @return string
     * 
     * @uses self::getEntityName()
     * @uses self::getEntityFullName()
     * @uses self::isEntityCompiled()
     * @uses self::compileEntity()
     */
    protected function createEntity(string $entity, string $parent): string
    {
        $entityName = $this->getEntityName($entity);
        $fullEntityName = $this->getEntityFullName($entityName);
        
        if(!$this->isEntityCompiled($fullEntityName)) {
            $this->compileEntity($entityName, $parent);
        }
        
        return $fullEntityName;
    }
    
    /**
     * Осуществляет проверку, объявлен ли табличной класс данной сущности
     * 
     * @param string $fullEntityName
     * @return bool
     * 
     * @uses self::getEntityTableClassName()
     */
    protected function isEntityCompiled(string $fullEntityName)
    {
        return class_exists(static::getEntityTableClassName($fullEntityName), false);
    }
    
    /**
     * Создает класс сущности, отнаследованный от другого класса
     * 
     * @param string $entity
     * @param string $parent
     */
    protected function compileEntity(string $entity, string $parent)
    {
        $code = sprintf(
            static::getClassTemplate(), 
            ($this->namespace ? sprintf('namespace %s;', $this->namespace) : ''), 
            static::getEntityTableClassName($entity),
            $parent,
            $this->iblockId
        );
        eval($code);
    }
    
    /**
     * Возвращает сгенерированное имя для сущности
     * 
     * @param string $entity Имя сущности
     * @param string $prefix Префикс
     * @return string
     */
    protected function getEntityName(string $entity, string $prefix = 'Iblock'): string
    {
        return sprintf('%s%s%u', $prefix, $entity, $this->iblockId);
    }
    
    /**
     * Возвращает полное наименование сущности (с пространством имен)
     * 
     * @param string $entity
     * @return string
     */
    protected function getEntityFullName($entity)
    {
        return sprintf('%s\%s', $this->namespace, $entity);
    }
    
    /**
     * Возвращает объект модели для получения данных инфоблока
     * 
     * @return \Ltm\Domain\Util\IblockOrm\Model
     * @throws \Bitrix\Main\SystemException Если инфоблок не найден
     */
    protected function getModel(): Model
    {
        $model = Model::get($this->iblockId);
        if(!$model) {
            throw new Main\SystemException(sprintf('Can not find iblock #%u', $this->id));
        }
        
        return $model;
    }
    
    /**
     * Преобразует наименование сущности к названию табличного класса
     * 
     * @param string $entity
     * @return string
     */
    protected static function getEntityTableClassName(string $entity): string
    {
        return sprintf('%sTable', $entity);
    }
    
    /**
     * Возвращает шаблон кода для компиляции
     * 
     * @static
     * @return string
     */
    protected static function getClassTemplate(): string
    {
        return <<<TEMPLATE
%s
    class %s extends \%s {
        public static function getIblockId(): int
        {
            return %u;
        }
    }
TEMPLATE;
    }
}