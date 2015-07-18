<?php

/*$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];*/

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
set_time_limit(0);

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form") || !CModule::IncludeModule("doka.meetings"))
    {
        $this->AbortResultCache();
        throw new Exception("Can't load modules iblock form");
    }

use Doka\Meetings\Settings as DS;
use Doka\Meetings\Requests as DR;
use Doka\Meetings\Timeslots as DT;
use Doka\Meetings\Wishlists as DWL;

$arParams["IBLOCK_ID_EXHIB"] = 15;

//список выставок из Инфогруппы по id выставки из модуля
$arResult = array();
$fio_dates = array();
$arResult["EXHIB"] = array();
$rs = CIBlockElement::GetList(array("SORT"=>"ASC"),
        array("IBLOCK_ID"=>$arParams["IBLOCK_ID_EXHIB"], "ACTIVE"=>"Y"), false, false,
        array("ID", "IBLOCK_ID", "NAME", "CODE", "PROPERTY_STATUS", "PROPERTY_STATUS_G_M", "PROPERTY_USER_GROUP_ID", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_APP_ID", "PROPERTY_V_EN", "PROPERTY_APP_HB_ID"));
while($ar = $rs->Fetch()) {
    $arResult["EXHIB"][$ar["PROPERTY_APP_ID_VALUE"]] = $ar;
    if($ar["PROPERTY_APP_HB_ID_VALUE"]){
        $arResult["EXHIB"][$ar["PROPERTY_APP_HB_ID_VALUE"]] = $ar;
    }
}
$arResult["MAIL_LIST"] = array();
// список выставок из модуля и составление вишлистов
$rsExhibitions = DS::GetList(array(), array("ACTIVE" => 1)); //добавить "IS_LOCKED" => 0
while ($exhibition = $rsExhibitions->Fetch()) {
        $req_obj = new DR($exhibition['ID']);
        $wishlist_obj = new DWL($exhibition['ID']);
        $arResult["MAIL_LIST"][$exhibition['ID']] = array();
        $arResult["MAIL_LIST"][$exhibition['ID']]["PARTICIP"] = array();
        $arResult["MAIL_LIST"][$exhibition['ID']]["GUEST"] = array();
        $formId = $exhibition['FORM_ID'];
        $propertyNameParticipant = $exhibition['FORM_RES_CODE'];//свойство участника
        $fio_datesPart = array();
        $fio_datesPart[0][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_446', $formId);//Имя участника
        $fio_datesPart[0][1] = CFormMatrix::getAnswerRelBase(84 ,$formId);
        $fio_datesPart[1][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_551', $formId);//Фамилия участника
        $fio_datesPart[1][1] = CFormMatrix::getAnswerRelBase(85 ,$formId);
        $fio_datesPart[2][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_859', $formId);//Email участника
        $fio_datesPart[2][1] = CFormMatrix::getAnswerRelBase(89 ,$formId);
        $HB_TEG = '';
        if($exhibition['IS_HB']){
            $HB_TEG = ' HB session';
        }
       
        //Список свободных участников и гостей
        $freeParticip = $req_obj->getUsersFreeTimesByGroup($exhibition["MEMBERS_GROUP"]);
        $freeGuest = $req_obj->getUsersFreeTimesByGroup($exhibition["GUESTS_GROUP"]);

        $allGuest = array();
        $allParticip = array();
        foreach($freeParticip as $personID => $personInfo) {
            $curWish = $wishlist_obj->getWishListToMail($personID);
            $meetCompany = $req_obj->getAllCompaniesMeet($personID);
            while($companyWish = $curWish->Fetch()){
                /* У компании из подходящего вишлиста есть свободный слот */
                if(isset($freeGuest[ $companyWish["USER"] ]) && !empty( array_intersect($personInfo["TIMES"], $freeGuest[ $companyWish["USER"] ]["TIMES"]) ) && !in_array($companyWish["USER"], $meetCompany)){
                    $arResult["MAIL_LIST"][$exhibition['ID']]["PARTICIP"][$personID][ $companyWish["USER"] ] = $companyWish["USER"];
                    $allGuest[ $companyWish["USER"] ] = $companyWish["USER"];
                    $allParticip[ $personID ] = $personID;
                }
            }
        }
        //Список свободных гостей
        foreach($freeGuest as $personID => $personInfo) {
            $curWish = $wishlist_obj->getWishListToMail($personID);
            $meetCompany = $req_obj->getAllCompaniesMeet($personID);
            while($companyWish = $curWish->Fetch()){
                /* У компании из подходящего вишлиста есть свободный слот */
                if(isset($freeGuest[ $companyWish["USER"] ]) && !empty(array_intersect($personInfo["TIMES"], $freeParticip[ $companyWish["USER"] ]["TIMES"])) && !in_array($companyWish["USER"], $meetCompany)){
                    $arResult["MAIL_LIST"][$exhibition['ID']]["GUEST"][$personID][ $companyWish["USER"] ] = $companyWish["USER"];
                    $allParticip[ $companyWish["USER"] ] = $companyWish["USER"];
                    $allGuest[ $personID ] = $personID;
                }
            }
        }
    if(!empty($allParticip) && !empty($allGuest)){
        /*Получаем информацию о гостях*/
        $arFilter = array(
            "GROUPS_ID" => $exhibition["GUESTS_GROUP"],
            "ID" => implode(" | ", $allGuest),
            "ACTIVE" => "Y"
        );

        $arParameters = array(
            "FIELDS" => array("ID", "EMAIL", "WORK_COMPANY", "NAME", "LAST_NAME"),
            "SELECT" => array($propertyNameParticipant, "UF_FIO")
        );
        $allGuest = array();
        $rsUsers = CUser::GetList(($by="work_company"), ($order="asc"), $arFilter, $arParameters);
        while($curUser = $rsUsers->Fetch()){
            $allGuest[ $curUser["ID"] ] = array(
                "EMAIL" => $curUser["EMAIL"],
                "FIO" => $curUser["UF_FIO"],
                "COMPANY" => $curUser["WORK_COMPANY"],
            );
        }
        /* Получаем информацию об участниках */
        $arFilter = array(
            "GROUPS_ID" => $exhibition["MEMBERS_GROUP"],
            "ID" => implode(" | ", $allParticip),
            "ACTIVE" => "Y"
        );
        $arParameters = array(
            "FIELDS" => array("ID", "EMAIL", "WORK_COMPANY", "NAME", "LAST_NAME"),
            "SELECT" => array($propertyNameParticipant, "UF_FIO")
        );
        $allParticip = array();
        $linksParticip = array();
        $allResultsForm = array();
        $rsUsers = CUser::GetList(($by="work_company"), ($order="asc"), $arFilter, $arParameters);
        while($curUser = $rsUsers->Fetch()){
            $allParticip[ $curUser["ID"] ] = array(
                "EMAIL" => "",
                "FIO" => "",
                "COMPANY" => $curUser["WORK_COMPANY"],
            );
            $allResultsForm[] = $curUser[ $propertyNameParticipant ];
            $linksParticip[ $curUser[ $propertyNameParticipant ] ] = $curUser["ID"];
        }


        /* Получаем данные из форм */
        CForm::GetResultAnswerArray(
            $formId,
            $arResult["FORM_RESULT_COMMON"]["QUESTIONS"],
            $arResult["FORM_RESULT_COMMON"]["ANSWERS"],
            $arResult["FORM_RESULT_COMMON"]["ANSWERS2"],
            array("RESULT_ID" => implode("|", $allResultsForm))
        );
        foreach($arResult["FORM_RESULT_COMMON"]["ANSWERS2"] as $resId => $reValue){
            $allParticip[ $linksParticip[$resId]  ]["EMAIL"] = $reValue[$fio_datesPart[2][0]][0]["USER_TEXT"];
            $allParticip[ $linksParticip[$resId]  ]["FIO"] = $reValue[$fio_datesPart[0][0]][0]["USER_TEXT"]." ".$reValue[$fio_datesPart[1][0]][0]["USER_TEXT"];
        }
    }
    /* ОТСЫЛКА сообщений по выставке */
    foreach ($arResult["MAIL_LIST"][$exhibition['ID']]["PARTICIP"] as $userId => $userInfo) {
        $arFieldsMes = array(
            "EXIB" => $arResult["EXHIB"][$exhibition['ID']]["PROPERTY_V_EN_VALUE"].$HB_TEG,
            "EMAIL" => $allParticip[ $userId ]["EMAIL"],
            "COMPANY" => array(),
        );
        foreach ($userInfo as $key => $value) {
            $arFieldsMes["COMPANY"][] = $allGuest[ $key ]["COMPANY"];
        }
        $arFieldsMes["COMPANY"] = implode(", ", $arFieldsMes["COMPANY"]);
        echo "<pre>";
        print_r($arFieldsMes);
        echo "</pre>";
        //CEvent::Send("FREE_FROM_WISHLIST","s1",$arFieldsMes);
    }
    foreach ($arResult["MAIL_LIST"][$exhibition['ID']]["GUEST_IN"] as $userId => $userInfo) {
        $arFieldsMes = array(
            "EXIB" => $arResult["EXHIB"][$exhibition['ID']]["PROPERTY_V_EN_VALUE"].$HB_TEG,
            "EMAIL" => $allGuest[ $userId ]["EMAIL"],
            "COMPANY" => array(),
        );
        foreach ($userInfo as $key => $value) {
            $arFieldsMes["COMPANY"][] = $allParticip[ $key ]["COMPANY"];
        }
        $arFieldsMes["COMPANY"] = implode(", ", $arFieldsMes["COMPANY"]);
        echo "<pre>";
        print_r($arFieldsMes);
        echo "</pre>";
        //CEvent::Send("FREE_FROM_WISHLIST","s1",$arFieldsMes);
    }
}


?>

