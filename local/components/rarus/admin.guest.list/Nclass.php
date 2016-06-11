<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Iblock;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\Loader;

class AdminGuestList extends CBitrixComponent
{
  public function onPrepareComponentParams($arParams)
  {
    if(!Loader::includeModule("iblock") || !Loader::includeModule("form"))
    {
      $this->AbortResultCache();
      throw new SystemException("Can't load modules iblock form");
    }
    if(empty($arParams["CACHE_TIME"])) {
      $arParams["CACHE_TIME"] = 3600;
    }
    if(empty($arParams["IBLOCK_ID_EXHIB"])) {
      $arParams["IBLOCK_ID_EXHIB"] = 15;
    }
    if(empty($arParams["EXHIBIT_CODE"])) {
      $arParams["EXHIBIT_CODE"] = $_REQUEST["EXHIBIT_CODE"];
    }
    if(empty($arParams["GUEST_FORM_ID"])) {
      $arParams["GUEST_FORM_ID"] = 10;
    }

    if(empty($arParams["ACT"])) {
      $arParams["ACT"] = $_REQUEST["ACT"];//off morning evening hostbuy
    }
    if(!in_array($arParams["ACT"], ["spam", "off", "morning", "evening", "hostbuy"])) {
      throw new Exception("Incorrect ACT");
    }

    return $arParams;
  }

  public function executeComponent()
  {
    if(empty($this->arParams)) {
      return false;
    }
    //Получаем данные по выставке
    $arSelect = ["ID", "NAME", "CODE"];
    $arFilter = [
      "IBLOCK_ID" => $this->arParams["IBLOCK_ID_EXHIB"],
      "CODE" => $this->arParams["EXHIBIT_CODE"]
    ];
    $exList = Iblock\ElementTable::getRow([
      'filter' => $arFilter,
      'select' => $arSelect,
      'runtime' => [
        'PROPERTY' => [
          'data_type' => 'Bitrix\Iblock\PropertyTable',
          'reference' => ['=this.IBLOCK_ID' => 'ref.IBLOCK_ID'],
          'join_type' => "LEFT",
        ],
      ],
    ]);

  }
}
