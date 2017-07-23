<?php

namespace Ltm\Domain\Util\IblockOrm;

trait AbstractIblockIdGetterTrait
{
    /**
     * Возвращает идентификатор инфоблока
     * 
     * @return int
     */
    abstract public static function getIblockId(): int;
    
    /**
     * Возвращает объект модели текущего инфоблока
     * 
     * @return \Ltm\Domain\Util\IblockOrm\Model
     * 
     * @throws \Bitrix\Main\SystemException Если инфоблок не найден
     */
    public static function getIblockModel(): Model
    {
        $id = static::getIblockId();
        $model = Model::get($id);
        if(!$model) {
            throw new \Bitrix\Main\SystemException(sprintf('Can not find iblock #%u data', $id));
        }
        
        return $model;
    }
}