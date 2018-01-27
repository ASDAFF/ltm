<?php

namespace Ltm\Domain\IblockOrm\PropertyProvider;

use Bitrix\Iblock;
use Bitrix\Main;
use Ltm\Domain\IblockOrm\Manager;

/**
 * Абстрактный класс преобразователя описаний свойств инфоблока в поля сущностей
 */
abstract class AbstractProvider
{
    /**
     * Возвращает набор полей по описанию свойства
     * 
     * @param array $arProperty
     * @return array
     */
    public function getPropertyFields(array $arProperty): array
    {
        $result = array(
            $this->getValueField($arProperty),
            $this->getValueIdField($arProperty)
        );
        
        $ref = $this->getReferenceField($arProperty);
        if($ref) {
            $result[] = $ref;
        }
        
        if($arProperty['WITH_DESCRIPTION'] == 'Y') {
            $result[] = $this->getDescriptionField($arProperty);
        }
        
        return $result;
    }
    
    /**
     * Возвращает поле значения свойства
     */
    abstract public function getValueField(array $arProperty);
    
    /**
     * Возвращает поле идентификатора значения свойства
     */
    abstract public function getValueIdField(array $arProperty);
    
    /**
     * Возвращает поле описания значения
     */
    abstract public function getDescriptionField(array $arProperty);
    
    /**
     * Возвращает реальное наименование поля, содержащего значение, по которому
     * осуществляется связка с другой сущностью
     */
    abstract public function getReferenceValue(array $arProperty);
    
    /**
     * Возвращает поле привязки к сущности
     * 
     * @param array $arProperty
     * @return \Bitrix\Main\Entity\ReferenceField|null
     * 
     * @uses self::createReferenceField()
     * @uses self::getElementReferenceEntity()
     * @uses self::getFileReferenceEntity()
     * @uses self::getListReferenceEntity()
     * @uses self::getSectionReferenceEntity()
     * @uses self::getUserReferenceEntity()
     */
    public function getReferenceField(array $arProperty)
    {
        $fieldName = $this->getReferenceFieldName($arProperty);
        $referenceKey = $this->getReferenceValue($arProperty);
        
        if($arProperty['PROPERTY_TYPE'] === Iblock\PropertyTable::TYPE_ELEMENT) {
            return $this->createReferenceField(
                $fieldName, 
                $this->getElementReferenceEntity($arProperty), 
                $referenceKey
            );
        } elseif($arProperty['PROPERTY_TYPE'] === Iblock\PropertyTable::TYPE_FILE) {
            return $this->createReferenceField(
                $fieldName, 
                $this->getFileReferenceEntity($arProperty), 
                $referenceKey
            );
        } elseif($arProperty['PROPERTY_TYPE'] === Iblock\PropertyTable::TYPE_LIST) {
            return $this->createReferenceField(
                $fieldName, 
                $this->getListReferenceEntity($arProperty), 
                $referenceKey
            );
        } elseif($arProperty['PROPERTY_TYPE'] === Iblock\PropertyTable::TYPE_SECTION) {
            return $this->createReferenceField(
                $fieldName, 
                $this->getSectionReferenceEntity($arProperty), 
                $referenceKey
            );
        } elseif(
            $arProperty['PROPERTY_TYPE'] === Iblock\PropertyTable::TYPE_STRING
            && $arProperty['USER_TYPE'] === 'ElementXmlID'
        ) {
            return $this->createReferenceField(
                $fieldName, 
                $this->getElementReferenceEntity($arProperty), 
                $referenceKey,
                'XML_ID'
            );
        } elseif(
            $arProperty['PROPERTY_TYPE'] === Iblock\PropertyTable::TYPE_STRING
            && $arProperty['USER_TYPE'] === 'User'
        ) {
            return $this->createReferenceField(
                $fieldName, 
                $this->getUserReferenceEntity($arProperty), 
                $referenceKey
            );
        }
        
        return null;
    }
    
    /**
     * Возвращает наименование поля описания значения
     * 
     * @param array $arProperty
     * @return string
     * 
     * @uses self::getValueFieldName()
     */
    protected function getDescriptionFieldName(array $arProperty): string
    {
        return sprintf('%s_DESCRIPTION', $this->getValueFieldName($arProperty));
    }
    
    /**
     * Возвращает наименование поля, содержащего объект связанной сущности
     * 
     * @param array $arProperty
     * @return string
     * 
     * @uses self::getValueFieldName()
     */
    protected function getReferenceFieldName(array $arProperty): string
    {
        return sprintf('%s_REFERENCE', $this->getValueFieldName($arProperty));
    }
    
    /**
     * Возвращает наименование поля, содержащего идентификатор значения свойства
     * 
     * @param array $arProperty
     * @return string
     * 
     * @uses self::getValueFieldName()
     */
    protected function getValueIdFieldName(array $arProperty): string
    {
        return sprintf('%s_VALUE_ID', $this->getValueFieldName($arProperty));
    }
    
    /**
     * Возвращает наименование поля, содержащего значение свойства. Совпадает
     * с кодом свойства, или если код не задан — PROP_<ID>
     * 
     * @param array $arProperty
     * @return string
     */
    protected function getValueFieldName(array $arProperty): string
    {
        if(!empty($arProperty['CODE'])) {
            return $arProperty['CODE'];
        }
        
        return sprintf('PROP_%u', $arProperty['ID']);
    }
    
    /**
     * Возвращает код сущности элементов инфоблока по переданному свойству
     * 
     * @param array $arProperty
     * @return string
     */
    protected function getElementReferenceEntity(array $arProperty = null)
    {
        if(!empty($arProperty) && !empty($arProperty['LINK_IBLOCK_ID'])) {
            $entityProvider = $this->getIblockEntityProvider((int) $arProperty['LINK_IBLOCK_ID']);
            return $entityProvider->getElementEntityName();
        }
        
        return '\Bitrix\Iblock\Element';
    }
    
    /**
     * Возвращает код сущности файлов
     * 
     * @param array $arProperty
     * @return string
     */
    protected function getFileReferenceEntity(array $arProperty = null)
    {
        return '\Bitrix\Main\File';
    }
    
    /**
     * Возвращает код сущности значений вариантов типа список
     * 
     * @param array $arProperty
     * @return string
     */
    protected function getListReferenceEntity(array $arProperty = null)
    {
        return '\Bitrix\Iblock\PropertyEnumeration';
    }
    
    /**
     * Возвращает код сущности секций для переданного свойства
     * 
     * @param array $arProperty
     * @return string
     */
    protected function getSectionReferenceEntity(array $arProperty = null)
    {
        if(!empty($arProperty) && !empty($arProperty['LINK_IBLOCK_ID'])) {
            $entityProvider = $this->getIblockEntityProvider((int) $arProperty['LINK_IBLOCK_ID']);
            return $entityProvider->getSectionEntityName();
        }
        
        return '\Bitrix\Iblock\Section';
    }
    
    /**
     * Возвращает код сущности пользователей
     * 
     * @param array $arProperty
     * @return string
     */
    protected function getUserReferenceEntity(array $arProperty = null): string
    {
        return '\Bitrix\Main\User';
    }
    
    /**
     * Обертка для создания поля привязки к другой сущности
     * 
     * @param string $fieldName Наименование поля
     * @param string $entity Сущность
     * @param string $valueField Поле, содержащее значение ключа в текущей сущности
     * @param string $refField Поле, содержащее значение ключа в привязываемой сущности
     * @param string $joinType Тип привязки
     * 
     * @return \Bitrix\Main\Entity\ReferenceField
     */
    protected function createReferenceField(
        string $fieldName, 
        string $entity, 
        string $valueField, 
        string $refField = 'ID', 
        string $joinType = 'LEFT'
    ): Main\Entity\ReferenceField
    {
        return new Main\Entity\ReferenceField(
            $fieldName,
            $entity,
            array(
                sprintf('=this.%s', $valueField) => sprintf('ref.%s', $refField)
            ),
            array(
                'join_type' => $joinType
            )
        );
    }
    
    /**
     * Возвращает провайдер сущностей для переданного инфоблока
     * 
     * @param int $iblockId
     * @return \Ltm\Domain\IblockOrm\EntityProviderInterface
     */
    protected function getIblockEntityProvider(int $iblockId): \Ltm\Domain\IblockOrm\EntityProviderInterface
    {
        return Manager::getInstance()->getProvider($iblockId);
    }
}
