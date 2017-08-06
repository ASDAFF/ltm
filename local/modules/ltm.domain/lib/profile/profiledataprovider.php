<?php

namespace Ltm\Domain\Profile;

use Bitrix\Iblock;
use Bitrix\Main\Entity;
use Bitrix\Main\Application;
use Bitrix\Main\SystemException;
use Bitrix\Main\Data\Cache;

use Ltm\Domain\IblockOrm;

class ProfileDataProvider
{
  const QUESTION_IBLOCK_ID = 25;
  const ANSWER_SETTINGS_IBLOCK_ID = 26;

  public function getQuestionListBySectionId($sectionId)
  {
    $iblockId = $this->getQuestionIblockId();
    /** @var Main\Entity\DataManager $table */
    $table = IblockOrm\Manager::getInstance()->getProvider($iblockId)->getElementTableClassName();

    $filter = ["=IBLOCK_SECTION_ID" => $sectionId];
    $select = [
      "ID", "NAME", "CODE", "TYPE" => "PROPERTY.TYPE", "REGISTRATION"  => "REGISTRATION.TYPE",
      "NOT_EDITABLE"  => "NOT_EDITABLE.TYPE", "NOT_ADMIN_LIST"  => "NOT_ADMIN_LIST.TYPE",
      "HL_NAME"  => "HL_NAME.TYPE", "MULTIPLE"  => "MULTIPLE.TYPE"
    ];
    $query = new Main\Entity\Query($table::getEntity());

    $query ->setSelect($select)
      ->setFilter($filter);

    $queryResult = $query->exec()->fetchAll();
    $result = array_combine(array_column($queryResult, "ID"), $queryResult);

    return $result;
  }

  public function getQuestionIblockId()
  {
    return self::QUESTION_IBLOCK_ID;
  }

  /**
   * Возвращает структуру данных свойств инфоблока.
   *
   * @return array из 3Ключи — коды свойств, значения — идентификаторы
   */
  public function getPropertiesId($iblockId)
  {
    if(empty($iblockId)) {
      return [];
    }

    $cacheTime = 1 * 3600;
    $cacheId = "properties".$iblockId;
    $cacheDir = "properties";

    $cache = Cache::createInstance();
    if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
      $result = $cache->getVars();
    } elseif ($cache->startDataCache()) {
      $mainSectPropId = [];
      $propertiesMultiple = [];
      $propertiesRequired = [];

      $selectProp = ["ID", "CODE", "MULTIPLE", "IS_REQUIRED"];
      $mainSectPropRes = Iblock\PropertyTable::getList([
        'filter' => ["=IBLOCK_ID" => $iblockId],
        'select' => $selectProp,
      ]);
      while ($prop = $mainSectPropRes->fetch()) {
        $mainSectPropId[$prop["CODE"]] = $prop["ID"];

        if ($prop["MULTIPLE"] == "Y") {
          $propertiesMultiple[] = $prop["CODE"];
        }

        if ($prop["IS_REQUIRED"] == "Y") {
          $propertiesRequired[] = $prop["CODE"];
        }
      }
      $result = [
        "ALL" => $mainSectPropId,
        "MULTIPLE" => $propertiesMultiple,
        "REQUIRED" => $propertiesRequired
      ];
      $cache->endDataCache($result);
    }

    return $result;
  }
}