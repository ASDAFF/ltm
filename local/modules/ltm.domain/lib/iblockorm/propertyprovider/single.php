<?php

namespace Ltm\Domain\Util\IblockOrm\PropertyProvider;

use Bitrix\Iblock;
use Bitrix\Main\Entity;

/**
 * Класс, возвращающий набор полей для единичных значений
 */
class Single extends AbstractProvider 
{
    /**
     * Возвращает поле значения свойства
     * 
     * @param array $arProperty
     * @return \Bitrix\Main\Entity\Field
     */
    public function getValueField(array $arProperty)
    {
        $fieldName = $this->getValueFieldName($arProperty);
        $columnName = $this->getValueColumnName($arProperty);
        
        return $this->createValueField($arProperty, $columnName, $fieldName);
    }
    
    /**
     * Возвращает поле идентификатора значения свойства
     * 
     * @param array $arProperty
     * @return \Bitrix\Main\Entity\ExpressionField
     */
    public function getValueIdField(array $arProperty) 
    {
        return new Entity\ExpressionField(
			$this->getValueIdFieldName($arProperty),
            sprintf("CONCAT(%%s, ':', %u)", $arProperty['ID']),
            array(
                'IBLOCK_ELEMENT_ID'
            )
        );
    }
    
    /**
     * Возвращает поле описания значения свойства
     * 
     * @param array $arProperty
     * @return \Bitrix\Main\Entity\StringField
     */
    public function getDescriptionField(array $arProperty)
    {
        return new Entity\StringField(
            $this->getDescriptionFieldName($arProperty),
            array(
                'column_name' => $this->getDescriptionColumnName($arProperty)
            )
        );
    }
    
    /** @inheritDoc */
    public function getReferenceValue(array $arProperty)
    {
        return $this->getValueFieldName($arProperty);
    }
    
    /**
     * Возвращает наименование колонки описания значения
     * 
     * @param array $arProperty
     * @return string
     */
    protected function getDescriptionColumnName(array $arProperty)
    {
        return sprintf('DESCRIPTION_%u', $arProperty['ID']);
    }
    
    /**
     * Возвращает наименование колонки значения
     * 
     * @param array $arProperty
     * @return string
     */
    protected function getValueColumnName(array $arProperty)
    {
        return sprintf('PROPERTY_%u', $arProperty['ID']);
    }
    
    /**
     * Создает объект поля в зависимости от типа свойства
     * 
     * @param array $arProperty Данные свойства
     * @param string $columnName Наименование колонки в таблице значений свойств
     * @param string $fieldName Наименование поля
     * 
     * @return \Bitrix\Main\Entity\Field
     */
    protected function createValueField(array $arProperty, string $columnName, string $fieldName): Entity\Field
    {
        if($arProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_NUMBER) {
            return new Entity\FloatField($fieldName, array('column_name' => $columnName));
        } elseif(
            $arProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT
            || $arProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE
            || $arProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST
            || $arProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_SECTION
            || (
                $arProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING
                && $arProperty['USER_TYPE'] == 'User'
            )
        ) {
            return new Entity\IntegerField($fieldName, array('column_name' => $columnName));
        } elseif(
            $arProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING 
            && $arProperty['USER_TYPE'] == 'HTML'
        ) {
			return new Entity\TextField($fieldName, array(
				'column_name' => $columnName,
                'serialized' => true
            ));
        } elseif(
            $arProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING
            && $arProperty['USER_TYPE'] == 'Date'
        ) {
			return new Entity\DateField($fieldName, array('column_name' => $columnName));
        } elseif(
            $arProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING
            && $arProperty['USER_TYPE'] == 'DateTime'
        ) {
            return new Entity\DatetimeField($fieldName, array('column_name' => $columnName));
        }
        
        return new Entity\StringField($fieldName, array('column_name' => $columnName));
    }
}