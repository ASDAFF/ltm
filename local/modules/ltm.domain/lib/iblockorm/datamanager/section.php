<?php

namespace Ltm\Domain\IblockOrm\DataManager;

use Bitrix\Iblock;
use Ltm\Domain\IblockOrm\AbstractIblockIdGetterTrait;

/**
 * Абстрактный класс для табличных классов секций, возвращает идентификатор 
 * сущности пользовательских полей. Требует реализации методов, возвоащающих
 * идентификатор инфоблока
 */
abstract class Section extends Iblock\SectionTable
{
    use AbstractIblockIdGetterTrait;
    
    /**
     * Возвращает идентификатор сущности пользовательских полей для секций данного
     * инфоблока
     * 
     * @return string
     */
    public static function getUfId() 
    {
        return sprintf('IBLOCK_%u_SECTION', static::getIblockId());
    }
}