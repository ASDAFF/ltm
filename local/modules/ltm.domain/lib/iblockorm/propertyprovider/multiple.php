<?php

namespace Ltm\Domain\Util\IblockOrm\PropertyProvider;

use Bitrix\Iblock;
use Bitrix\Main;

/**
 * Класс для создания полей множественный свойств. Подходит также для свойств
 * инфоблоков 1.0, т.к. они хранятся в таблице подобной структуы
 */
class Multiple extends AbstractProvider
{
    /**
     * @var string Сущность, в которой хранятся данные значений 
     */
    protected $multipleEntity;
    
    /**
     * @param string $multipleEntity Сущность, содержащая данные множественных полей
     */
    public function __construct(string $multipleEntity)
    {
        $this->multipleEntity = $multipleEntity;
    }
    
    /** @inheritDoc */
    public function getPropertyFields(array $arProperty): array
    {
        return array_merge(
            array(
                $this->createRowReferenceField($arProperty)
            ),
            parent::getPropertyFields($arProperty)
        );
    }
    
    /** @inheritDoc */
    public function getValueField(array $arProperty)
    {
        return $this->createColumnExpression(
            $this->getValueFieldName($arProperty),
            $this->getRowReferenceFieldName($arProperty),
            $this->getValueColumn($arProperty)
        );
    }
    
    /** @inheritDoc */
    public function getValueIdField(array $arProperty)
    {
        return $this->createColumnExpression(
            $this->getValueIdFieldName($arProperty),
            $this->getRowReferenceFieldName($arProperty),
            'ID'
        );
    }
    
    /** @inheritDoc */
    public function getDescriptionField(array $arProperty)
    {
        return $this->createColumnExpression(
            $this->getDescriptionFieldName($arProperty),
            $this->getRowReferenceFieldName($arProperty),
            'DESCRIPTION'
        );
    }
    
    /** @inheritDoc */
    public function getReferenceValue(array $arProperty)
    {
        return sprintf(
            '%s.%s',
            $this->getRowReferenceFieldName($arProperty),
            $this->getValueColumn($arProperty)
        );
    }
    
    /**
     * Возвращает поле привязки к записям, содержащий значения переданного свойства
     * 
     * @param array $arProperty
     * @return \Bitrix\Main\Entity\ReferenceField
     */
    public function createRowReferenceField(array $arProperty): Main\Entity\ReferenceField
    {
        return new Main\Entity\ReferenceField(
            $this->getRowReferenceFieldName($arProperty),
            $this->multipleEntity,
            array(
                '=this.IBLOCK_ELEMENT_ID' => 'ref.IBLOCK_ELEMENT_ID',
                '=ref.IBLOCK_PROPERTY_ID' => new Main\DB\SqlExpression('?i', $arProperty['ID'])
            )
        );
    }
    
    /**
     * Создает поле-выражение для единообразного обращения к значениям свойства
     * 
     * @param string $fieldName Наименование поля
     * @param string $rowReferenceField Наименование поля — строки с данными
     * @param string $column Колонка, в котоой хранится значение
     * 
     * @return \Bitrix\Main\Entity\ExpressionField
     */
    protected function createColumnExpression(string $fieldName, string $rowReferenceField, string $column): Main\Entity\ExpressionField
    {
        return new Main\Entity\ExpressionField(
            $fieldName,
            '%s',
            array(
                sprintf(
                    '%s.%s',
                    $rowReferenceField,
                    $column
                )
            )
        );
    }
    
    /**
     * Возвращает наименование поля-ссылки на строки с данными свойства
     * 
     * @param array $arProperty
     * @return string
     * 
     * @uses self::getValueFieldName()
     */
    protected function getRowReferenceFieldName(array $arProperty): string
    {
        return sprintf('%s_VALUE_ROW', $this->getValueFieldName($arProperty));
    }
    
    /**
     * Возвращает колонку, в котоой хранится значение в зависимости от типа поля
     * 
     * @param array $arProperty
     * @return string
     */
    protected function getValueColumn(array $arProperty)
    {
        switch($arProperty['PROPERTY_TYPE']) {
            case Iblock\PropertyTable::TYPE_ELEMENT:
            case Iblock\PropertyTable::TYPE_NUMBER:
            case Iblock\PropertyTable::TYPE_SECTION:
                return 'VALUE_NUM';
                
            case Iblock\PropertyTable::TYPE_LIST:
                return 'VALUE_ENUM';
                
            default:
                return 'VALUE';
        }
    }
    
}