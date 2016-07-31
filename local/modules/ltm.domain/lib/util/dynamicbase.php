<?php

namespace Ltm\Domain\Util;

use Bitrix\Main\Entity\Base;
use Bitrix\Main\UserFieldTable;

class DynamicBase extends Base
{
    private static $seq = 0;
    
    private $id;
    
    public function __construct($dbTableName, array $arFieldsMap, $connectionName = null, $uts = false, $utm = false)
    {
        $this->id = self::$seq++;
        
        $this->dbTableName = $dbTableName;
        $this->fieldsMap = $arFieldsMap;
        $this->connectionName = ((!empty($connectionName)) 
            ? $connectionName 
            : 'default');
        $this->isUtm = $utm;
        $this->isUts = $uts;
        
        $this->postInitialize();
    }
    
    public function getCode()
    {
        return 'lv_dynamic_' . $this->id;
    }
    
    /**
     * @static
     * 
     * @param Bitrix\Main\Entity\Base $entity
     * @param string $ufID
     */
    public static function attachEntityUserFields(Base $entity, $ufID)
    {
        $entity->uf_id = $ufID;
        UserFieldTable::attachFields($entity, $ufID);
    }
}
