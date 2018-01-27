<?php

namespace Ltm\Domain\Profile;

use Bitrix\Iblock;
use Bitrix\Main;
use Bitrix\Main\{Entity, Application, SystemException, Data\Cache};

use Ltm\Domain\IblockOrm;

class ProfileDataProvider
{
  const QUESTION_IBLOCK_ID = 25;
  const ANSWER_SETTINGS_IBLOCK_ID = 26;

  public function getQuestionListBySectionId($sectionId)
  {
    $iblockId = $this->getQuestionIblockId();
    /** @var Main\Entity\DataManager $table */
    $provider = IblockOrm\Manager::getInstance()->getProvider($iblockId);
    $table = $provider->getElementTableClassName();

    $filter = ["=IBLOCK_SECTION_ID" => $sectionId];
    $select = [
      "ID", "NAME", "CODE", "TYPE" => "PROPERTY.TYPE_REFERENCE.XML_ID", "REGISTRATION"  => "PROPERTY.REGISTRATION",
      "NOT_EDITABLE"  => "PROPERTY.NOT_EDITABLE", "NOT_ADMIN_LIST"  => "PROPERTY.NOT_ADMIN_LIST",
      "HL_NAME"  => "PROPERTY.HL_NAME", "MULTIPLE"  => "PROPERTY.MULTIPLE"
    ];
    $query = new Main\Entity\Query($table::getEntity());

    $query ->setSelect($select)
      ->setFilter($filter);

    $queryResult = $query->exec()->fetchAll();
    $result = array_combine(array_column($queryResult, "CODE"), $queryResult);

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