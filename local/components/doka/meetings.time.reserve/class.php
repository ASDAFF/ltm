<?php

namespace Mts\Components;

use \CBitrixComponent;
use \Bitrix\Main\{Loader, LoaderException};
use \Bitrix\Main\Localization\Loc as Loc;

use \Doka\Meetings\Requests as DokaRequest;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
  die();
}

class MeetingsRequestReserve extends CBitrixComponent
{

  /**
  * подготавливает входные параметры
  * @param array $this->arParams
  * @return array
  */
  public function onPrepareComponentParams($arParams)
  {
    $result = parent::onPrepareComponentParams($arParams);

    return $result;
  }

  /**
  * подключает языковые файлы
  */
  public function onIncludeComponentLang()
  {
    $this->includeComponentLang(basename(__FILE__));
    Loc::loadMessages(__FILE__);
  }


  /**
  * проверяет подключение необходиимых модулей
  * @throws LoaderException
  */
  protected function checkModules()
  {
    if (!Loader::includeModule('doka.meetings')) {
      throw new LoaderException(Loc::getMessage('DOKA_MODULE_NOT_INSTALLED'));
    }
    if (!Loader::includeModule('form')) {
      throw new LoaderException(Loc::getMessage('F0RM_MODULE_NOT_INSTALLED'));
    }
    if (!Loader::includeModule('iblock')) {
      throw new LoaderException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    }
  }

  protected function getResult()
  {
    global $USER;
    $arResult = array();
    $arResult["TIME"] = intval($this->arParams["TIME"]);
    $arResult["USER_ID"] = intval($_REQUEST['id']);

    if (empty($this->arParams["APP_ID"])) {
      ShowError("404 Not Found");
      @define("ERROR_404", "Y");
      \CHTTP::SetStatus("404 Not Found");
    }

    if (!$USER->IsAuthorized()) {
      ShowError(GetMessage("ERROR_EMPTY_USER_ID"));
      return;
    }

    if ($arResult["TIME"] <= 0) {
      ShowError(GetMessage("ERROR_EMPTY_TIMESLOT_ID"));
      return;
    }

    $req_obj = new DokaRequest($this->arParams['APP_ID']);
    $statusReserve = $req_obj->getStatusCode($req_obj::STATUS_RESERVE);

    $arResult["APP"] = $this->arParams['APP_ID'];
    $arResult['USER_TYPE'] = $req_obj->getUserType();
    $arResult['IS_ACTIVE'] = !$req_obj->getOption('IS_LOCKED');

    if(!$arResult['IS_ACTIVE'] && $arResult['USER_TYPE'] != 'ADMIN') {
      $arResult['ERROR_MESSAGE'][] = GetMessage("EXHIBITION_BLOCKED");
      $this->IncludeComponentTemplate();
      return;
    }

    if (empty($arResult["USER_ID"]) || $arResult['USER_TYPE'] != 'ADMIN' )
      $arResult["USER_ID"] = $USER->GetID();

    if(isset($_REQUEST["type"]) && $_REQUEST["type"] == "p" && $USER->GetID() == 1){
      $arResult['USER_TYPE'] = "PARTICIP";
    }
    elseif(isset($_REQUEST["type"]) && $USER->GetID() == 1){
      $arResult['USER_TYPE'] = "GUEST";
    }

    if($arResult['USER_TYPE'] != "PARTICIP" && !$USER->isAdmin()) {
      ShowError(GetMessage("ERROR_NOT_PARTICIP"));
      return;
    }

// Проверяем существует ли такой таймслот
    $arResult['TIMESLOT'] = $req_obj->getMeetTimeslot($arResult["TIME"]);
    if (!$arResult['TIMESLOT']) {
      $arResult['ERROR_MESSAGE'][] = GetMessage($arResult['USER_TYPE'] . '_WRONG_TIMESLOT_ID');
    }
    $companyTimeslot = $req_obj->getAllTimesByComp($arResult["USER_ID"]);
    $arResult['TO_RESERVE'] = 'Y';
    if(isset($companyTimeslot[$arResult["TIME"]]) && $companyTimeslot[$arResult["TIME"]]['meet']['status'] == $statusReserve) {
      $arResult['TO_RESERVE'] = 'N';
    }

    $fields = array(
      'RECEIVER_ID' => $arResult["USER_ID"],
      'SENDER_ID' => $arResult["USER_ID"],
      'EXHIBITION_ID' => $this->arParams['APP_ID'],
      'TIMESLOT_ID' => $arResult['TIMESLOT']['id'],
      'STATUS' => '',
    );
    $arResult['SEND'] = 'N';
    if($arResult['TO_RESERVE'] == 'N') {
      $request = $req_obj->getActiveRequest($arResult["TIME"], $arResult["USER_ID"], $arResult["USER_ID"]);
      $req_obj->rejectRequest($request);
      $arResult['SEND'] = 'Y';
    } else {
      if(isset($companyTimeslot[$arResult["TIME"]])) {
        $arResult['ERROR_MESSAGE'][] = GetMessage("ERROR_TIMESLOT_BUSY");
        $this->IncludeComponentTemplate();
        return;
      }

      if($_REQUEST['confirm'] == 'Y') {
        $req_obj->reserveRequest($fields);
        $arResult['SEND'] = 'Y';
      }
    }

    return $arResult;
  }

  /**
   * @return array|mixed
   */
  public function executeComponent()
  {
    $this->onIncludeComponentLang();
    $this->checkModules();

    $this->arResult = $this->getResult();

    $this->includeComponentTemplate();
    return $this->arResult;
  }
}