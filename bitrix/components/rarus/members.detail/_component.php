<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB;
global $USER;
global $APPLICATION;

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;

if(!isset($arParams["LANG"]))
    $arParams["LANG"] = LANG;

if(!isset($arParams["IBLOCK_ID_EXHIB"]))
    $arParams["IBLOCK_ID_EXHIB"] = 15;

if(!isset($arParams["IBLOCK_ID_PHOTO"]))
    $arParams["IBLOCK_ID_PHOTO"] = 16;

if(!isset($arParams["FORM_COMMON_ID"]))
    $arParams["FORM_COMMON_ID"] = 3;

if(!isset($arParams["FORM_FIELD_ID_NAME"]))
    $arParams["FORM_FIELD_ID_NAME"] = 17;

if(!isset($arParams["FORM_FIELD_ID_PRIORAREA"]))
    $arParams["FORM_FIELD_ID_PRIORAREA"] = array(25, 26, 27, 28, 29, 30);

if(!isset($arParams["FORM_FIELD_ID_COUNTRY"]))
    $arParams["FORM_FIELD_ID_COUNTRY"] = 22;

if(!isset($arParams["FORM_FIELD_ID_CATEGORY"]))
    $arParams["FORM_FIELD_ID_CATEGORY"] = 19;

if(!isset($arParams["FORM_FIELD_ID_LOGIN"]))
    $arParams["FORM_FIELD_ID_LOGIN"] = 18;

if(!isset($arParams["FORM_FIELD_ID_COMPANY_OFFICIAL_ADDRESS"]))
    $arParams["FORM_FIELD_ID_COMPANY_OFFICIAL_ADDRESS"] = 20;

if(!isset($arParams["EXHIBIT_CODE_NAME_IN_REQUEST"]))
    $arParams["EXHIBIT_CODE_NAME_IN_REQUEST"] = "CODE";

if(!isset($arParams["MEMBER_CODE_NAME_IN_REQUEST"]))
    $arParams["MEMBER_CODE_NAME_IN_REQUEST"] = "ID";

isset($arParams["FORM_FIELD_ID_COMPANY_LOGO"]) or $arParams["FORM_FIELD_ID_COMPANY_LOGO"] = "100";

$arResult = array();

$arResult["ID"] = intval($_REQUEST[ $arParams["MEMBER_CODE_NAME_IN_REQUEST"] ]);

if($this->StartResultCache(false, array_merge($arParams, $arResult)))
{
	if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
		$this->AbortResultCache();
		throw new Exception("Can't load modules iblock form");
	}

	$arResult["FORM_RESULT_COMMON"] = array("QUESTIONS"=>array(), "ANSWERS"=>array());
    //������ ����������� ������� ����� "��������� ������ �������� ��� ��������"
    CForm::GetResultAnswerArray(
        $arParams["FORM_COMMON_ID"],
        $arResult["FORM_RESULT_COMMON"]["QUESTIONS"],
        $arResult["FORM_RESULT_COMMON"]["ANSWERS"],
        ($a = false),
	    array("RESULT_ID"=>$arResult["ID"]));

    if(empty($arResult["FORM_RESULT_COMMON"]["ANSWERS"])) {
        $this->AbortResultCache();
    	@define("ERROR_404", "Y");
		CHTTP::SetStatus("404 Not Found");
//  	    header("Status: 404 Not Found");
        echo 'Unknown member id';

    	return;
    }
    $arResult["MEMBER"] = reset($arResult["FORM_RESULT_COMMON"]["ANSWERS"]);

    $userLogin = reset($arResult["MEMBER"][ $arParams["FORM_FIELD_ID_LOGIN"] ]);
    $userLogin = $userLogin["USER_TEXT"];
    $arResult["TEXT"] = reset($arResult["MEMBER"][ $arParams["FORM_FIELD_ID_TEXT"] ]);
    $arResult["TEXT"] = $arResult["TEXT"]["USER_TEXT"];
    $arResult["NAME"] = reset($arResult["MEMBER"][ $arParams["FORM_FIELD_ID_NAME"] ]);
    $arResult["NAME"] = $arResult["NAME"]["USER_TEXT"];
    $arResult["SITE_URL"] = reset($arResult["MEMBER"][ $arParams["FORM_FIELD_ID_SITE_URL"] ]);
    $arResult["SITE_URL"] = $arResult["SITE_URL"]["USER_TEXT"];
    if(strlen($arResult["SITE_URL"]) < 8) $arResult["SITE_URL"] = "";
    $arResult["ADDRESS"] = reset($arResult["MEMBER"][ $arParams["FORM_FIELD_ID_COMPANY_OFFICIAL_ADDRESS"] ]);
    $arResult["ADDRESS"] = $arResult["ADDRESS"]["USER_TEXT"];

    //�������� ���� ��������
    if(isset($arResult["MEMBER"][ $arParams["FORM_FIELD_ID_COMPANY_LOGO"] ]) && !empty($arResult["MEMBER"][ $arParams["FORM_FIELD_ID_COMPANY_LOGO"] ])) {
        $logo = reset($arResult["MEMBER"][ $arParams["FORM_FIELD_ID_COMPANY_LOGO"] ]);
        if(isset($logo["USER_FILE_ID"]) && $logo["USER_FILE_ID"] > 0) {
        	$logo = CFile::GetFileArray($logo["USER_FILE_ID"]);
        	if(isset($logo["SRC"]) && $logo["SRC"]) {
        		$arResult["COMPANY_LOGO"] = array("ID"=>$logo["ID"], "SRC"=>$logo["SRC"]);
        	}
        }
    }

    //�������� ����
    $rs = CIBlockSection::GetList(array(), array("IBLOCK_ID"=>$arParams["IBLOCK_ID_PHOTO"], "NAME"=>$arResult["NAME"]), false, array("ID"), array("nTopCount"=>1));
    if($ar = $rs->Fetch()) {
        $arResult["PHOTOS"] = array();
        $rs = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>$arParams["IBLOCK_ID_PHOTO"], "SECTION_ID"=>$ar["ID"], "ACTIVE"=>"Y"), false, false, array("ID", "NAME", "DETAIL_PICTURE", "PREVIEW_PICTURE"));
        while($ar = $rs->GetNext(true, false)) {
            if(!$ar["PREVIEW_PICTURE"] && !$ar["DETAIL_PICTURE"]) continue;

            if($ar["DETAIL_PICTURE"]) {
                $ar["DETAIL_PICTURE"] = CFile::GetFileArray($ar["DETAIL_PICTURE"]);
            }

            if($ar["PREVIEW_PICTURE"]) {
                $ar["PREVIEW_PICTURE"] = CFile::GetFileArray($ar["PREVIEW_PICTURE"]);
            } else {
                $ar["PREVIEW_PICTURE"] = $ar["DETAIL_PICTURE"];
            }

            if(empty($ar["DETAIL_PICTURE"])) {
                $ar["DETAIL_PICTURE"] = $ar["PREVIEW_PICTURE"];
            }

        	$arResult["PHOTOS"][] = $ar;
        }
    }

	$this->IncludeComponentTemplate();
}
?>
