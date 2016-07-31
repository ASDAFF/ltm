<?php

namespace Ltm\Domain\Util;

use Bitrix\Iblock;
use Bitrix\Main\DB;
use Bitrix\Main\Entity;

class IBlockHelper
{
    protected static $propEntityCache = array();

    protected static $idCache = array();

    /**
     * @param int $id
     * @return Bitrix\Main\Entity\Base
     */
    public static function getEntity($id)
    {
        $entity = clone Iblock\ElementTable::getEntity();

        if(empty(static::$propEntityCache[$id])) {
            static::$propEntityCache[$id] = BitrixOrmHelper::getIBlockPropertiesEntity($id);
        }

        $entity->addField(array(
            'data_type' => static::$propEntityCache[$id],
            'reference' => array(
                '=this.ID' => 'ref.IBLOCK_ELEMENT_ID'
            )
        ), 'PROPERTY');

        return $entity;
    }

    /**
     * @static
     *
     * @param string $code
     * @param string $type
     *
     * @return int|null
     */
    public static function getIDByCode($code, $type = '')
    {
        $cacheKey = sprintf('%s%s', $code, !!$type ? ( ':' . $type) : '');

        if(empty(static::$idCache[$cacheKey])) {
            $result = BitrixOrmHelper::getIblockIdByCode($code, $type);

            static::$idCache[$cacheKey] = $result;
        }

        return static::$idCache[$cacheKey];
    }

    /**
     * Возвращает объект `Query`, инициализированный для выборки дочерних секций
     * для секции `$sectionID`. Данные родительской секции `$sectionID` доступны
     * через поле `PARENT_SECTION_REF`.
     *
     * В случае, если не передана сущность секции, будет использоваться
     * стандартная сущность из класса `Bitrix\Iblock\SectionTable`.
     *
     * @param int $sectionID
     * @param \Bitrix\Main\Entity\Base|null $sectionEntity
     * @return \Bitrix\Main\Entity\Query
     */
    public static function getSectionChildrenQuery($sectionID, Entity\Base $sectionEntity = null)
    {
        if(is_null($sectionEntity)) {
            $sectionEntity = Iblock\SectionTable::getEntity();
        }

        $q = new Entity\Query($sectionEntity);
        $q->registerRuntimeField('PARENT_SECTION_REF', array(
            'data_type' => $sectionEntity,
            'reference' => array(
                '=ref.ID' => new DB\SqlExpression('?i', $sectionID),
                '=this.IBLOCK_ID' => 'ref.IBLOCK_ID',
                '>this.LEFT_MARGIN' => 'ref.LEFT_MARGIN',
                '<this.LEFT_MARGIN' => 'ref.RIGHT_MARGIN'
            ),
            'join_type' => 'INNER'
        ));

        return $q;
    }

    /**
     * Возвращает объект `Query`, инициализированный для выборки родительских
     * секций для секции `$sectionID`. Данные секции `$sectionID` доступны через
     * поле `CHILD_SECTION_REF`.
     *
     * Если необходимо получить данные секции `$sectionID` в общей выборке,
     * следует передать положительное значение `$withSelf`.
     *
     * @param int $sectionID
     * @param \Bitrix\Main\Entity\Base|null $sectionEntity
     * @param bool $withSelf
     *
     * @return \Bitrix\Main\Entity\Query
     */
    public static function getSectionParentsQuery($sectionID, Entity\Base $sectionEntity = null, $withSelf = false)
    {
        if(is_null($sectionEntity)) {
            $sectionEntity = Iblock\SectionTable::getEntity();
        }

        $q = new Entity\Query($sectionEntity);
        $q->registerRuntimeField('CHILD_SECTION_REF', array(
            'data_type' => $sectionEntity,
            'reference' => array(
                '=ref.ID' => new DB\SqlExpression('?i', $sectionID),
                '=this.IBLOCK_ID' => 'ref.IBLOCK_ID',
                sprintf('<%sthis.LEFT_MARGIN', (!!$withSelf ? '=' : '')) => 'ref.LEFT_MARGIN',
                '>this.RIGHT_MARGIN' => 'ref.LEFT_MARGIN'
            ),
            'join_type' => 'INNER'
        ));

        return $q;
    }

    /**
     * Возвращает объект `Query`, инициализированный для выборки родительских
     * секций для секции `$sectionID`, включая саму эту секцию.
     *
     * @param int $sectionID
     * @param \Bitrix\Main\Entity\Base|null $sectionEntity
     * @return \Bitrix\Main\Entity\Query
     *
     * @uses getSectionParentsQuery()
     */
    public static function getSectionSelfParentsQuery($sectionID, Entity\Base $sectionEntity = null)
    {
        return static::getSectionParentsQuery($sectionID, $sectionEntity, true);
    }
}
