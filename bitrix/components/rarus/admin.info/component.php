<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB;
global $USER;
global $APPLICATION;

define('FID_INBOX', 1);
define('FID_SENT', 3);
define('UID', 1);

$arResult["ERROR_MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
    $arParams["PATH_TO_KAB"] = "/admin/";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
    $arParams["AUTH_PAGE"] = "/admin/login.php";
}

if(strLen($arParams["EXHIB_IBLOCK_ID"])<=0){
    $arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ��������!<br />";
}

if(!($USER->IsAuthorized()))
{
    $arResult["ERROR_MESSAGE"] = "�� �� ������������!<br />";
}

if(!($USER->IsAdmin()))
{
    $arResult["ERROR_MESSAGE"] = "�� �� �������������!<br />";
}

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form") || CModule::IncludeModule("forum"))
{
    $arResult["ERROR_MESSAGE"] = "������ ����������� �������!<br />";
}

$arResult = array();


//��������� ��������
$arFilter = array(
	"IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
    "ACTIVE" => "Y"
);

if(isset($arParams["EXHIB_CODE"]) && strlen($arParams["EXHIB_CODE"]) > 0)
{
    $arFilter["CODE"] = trim($arParams["EXHIB_CODE"]);
}

$arSelect = array(
	"ID",
    "NAME",
    "IBLOCK_ID",
    "CODE",
    "PROPERTY_*"
);

$rsElement = CIBlockElement::GetList(array("sort" => "asc"),$arFilter, false, false, $arSelect);
while($obElement = $rsElement->GetNextElement(true, false))
{
    $arItem = $obElement->GetFields();
    $arItem["PROPERTIES"] = $obElement->GetProperties();

    $resIdPropName = CFormMatrix::getPropertyIDByExh($arItem["ID"]);
    //��������� ������ � ������ �������������
    $arGroupsPropName = array("USER_GROUP_ID", "UC_PARTICIPANTS_GROUP", "C_GUESTS_GROUP", "UC_GUESTS_GROUP", "PARTICIPANT_SPAM_GROUP", "GUEST_SPAM_GROUP");

    foreach ($arGroupsPropName as $propName)
    {
    	if(intval($arItem["PROPERTIES"][$propName]["VALUE"]) > 0)
    	{
    	    $rsGroup = CGroup::GetByID($arItem["PROPERTIES"][$propName]["VALUE"], "Y");
    	    $arGroup = $rsGroup->Fetch();
    	    $arItem["GROUPS"][$arGroup["ID"]] = $arGroup;
    	}
    }


    //��������� ������ �� �������������� ������
    $guestGroupID = $arItem["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"];


    if($guestGroupID)
    {
    	$arFilter = array(
    		"GROUPS_ID" => $guestGroupID,
    	);

    	$arParameters = array(
    		"FIELDS" => array("ID", "LOGIN", "EMAIL", "WORK_COMPANY", "ACTIVE"),
    	    "SELECT" => array("UF_*")
    	    //UF_ID - ������ �����, UF_ID2 - ����, UF_ID3 - ����, UF_ID4 - ������, UF_ID5 - ������ �����, UF_MR - ����� �� ����, UF_EV - ����� �� �����, UF_HB -Hosted Buyers
    	);

    	$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $arFilter, $arParameters);

    	while($arUsers = $rsUsers->Fetch())
    	{
    	        if($arUsers["UF_MR"])
    	        {
    	            $arItem["GUESTS"]["CONFIRMED"]["MORNING"][$arUsers["ID"]] = $arUsers["ID"];
    	        }
    	        if($arUsers["UF_EV"])
    	        {
    	            $arItem["GUESTS"]["CONFIRMED"]["EVENING"][$arUsers["ID"]] = $arUsers["ID"];
    	        }
    	        if($arUsers["UF_HB"])
    	        {
    	            $arItem["GUESTS"]["CONFIRMED"]["HOSTED_BUYERS"][$arUsers["ID"]] = $arUsers["ID"];
    	        }
    	        $arItem["GUESTS"]["CONFIRMED"]["ALL"][$arUsers["ID"]] = $arUsers["ID"];
    	}
    }

    //��������� ������ �� ���������������� ������
    $guestGroupID = $arItem["PROPERTIES"]["UC_GUESTS_GROUP"]["VALUE"];

    if($guestGroupID)
    {
        $arFilter = array(
            "GROUPS_ID" => $guestGroupID,
        );

        $arParameters = array(
            "FIELDS" => array("ID", "LOGIN", "EMAIL", "WORK_COMPANY", "ACTIVE"),
            "SELECT" => array("UF_*")
            //UF_ID - ������ �����, UF_ID2 - ����, UF_ID3 - ����, UF_ID4 - ������, UF_ID5 - ������ �����, UF_MR - ����� �� ����, UF_EV - ����� �� �����, UF_HB -Hosted Buyers
        );

        $rsUsers = CUser::GetList(($by="id"), ($order="asc"), $arFilter, $arParameters);

        while($arUsers = $rsUsers->Fetch())
        {
            if(intval($arUsers[$resIdPropName]))
            {
                $arItem["GUESTS"]["UNCONFIRMED"][$arUsers["ID"]] = $arUsers["ID"];
            }
        }
    }

    //��������� ������ �� ���� ������
    $guestGroupID = $arItem["PROPERTIES"]["GUEST_SPAM_GROUP"]["VALUE"];

    if($guestGroupID)
    {
        $arFilter = array(
            "GROUPS_ID" => $guestGroupID,
        );

        $arParameters = array(
            "FIELDS" => array("ID", "LOGIN", "EMAIL", "WORK_COMPANY", "ACTIVE"),
            "SELECT" => array("UF_*")
            //UF_ID - ������ �����, UF_ID2 - ����, UF_ID3 - ����, UF_ID4 - ������, UF_ID5 - ������ �����, UF_MR - ����� �� ����, UF_EV - ����� �� �����, UF_HB -Hosted Buyers
        );

        $rsUsers = CUser::GetList(($by="id"), ($order="asc"), $arFilter, $arParameters);

        while($arUsers = $rsUsers->Fetch())
        {

             $arItem["GUESTS"]["SPAM"][$arUsers["ID"]] = $arUsers["ID"];

        }
    }


    //��������� ������ �������������� ����������
    $participantGroupID = $arItem["PROPERTIES"]["USER_GROUP_ID"]["VALUE"];

    if($participantGroupID)
    {
        $arFilter = array(
            "GROUPS_ID" => $participantGroupID,
        );

        $arParameters = array(
            "FIELDS" => array("ID", "LOGIN", "EMAIL", "WORK_COMPANY", "ACTIVE"),
            "SELECT" => array("UF_*")
            //UF_ID - ������ �����, UF_ID2 - ����, UF_ID3 - ����, UF_ID4 - ������, UF_ID5 - ������ �����, UF_MR - ����� �� ����, UF_EV - ����� �� �����, UF_HB -Hosted Buyers
        );

        $rsUsers = CUser::GetList(($by="id"), ($order="asc"), $arFilter, $arParameters);

        while($arUsers = $rsUsers->Fetch())
        {
             $arItem["PARTICIPANT"]["CONFIRMED"][$arUsers["ID"]] = $arUsers["ID"];
        }
    }

    //��������� ������ ���������������� ����������
    $participantGroupID = $arItem["PROPERTIES"]["UC_PARTICIPANTS_GROUP"]["VALUE"];

    if($participantGroupID)
    {
        $arFilter = array(
            "GROUPS_ID" => $participantGroupID,
        );

        $arParameters = array(
            "FIELDS" => array("ID", "LOGIN", "EMAIL", "WORK_COMPANY", "ACTIVE"),
            "SELECT" => array("UF_*")
            //UF_ID - ������ �����, UF_ID2 - ����, UF_ID3 - ����, UF_ID4 - ������, UF_ID5 - ������ �����,UF_ID11 - ��������� ������������� ������ ����� - 2015, UF_MR - ����� �� ����, UF_EV - ����� �� �����, UF_HB -Hosted Buyers
        );

        $rsUsers = CUser::GetList(($by="id"), ($order="asc"), $arFilter, $arParameters);

        while($arUsers = $rsUsers->Fetch())
        {
                $arItem["PARTICIPANT"]["UNCONFIRMED"][$arUsers["ID"]] = $arUsers["ID"];
        }
    }

    //��������� ������ ���� ����������
    $participantGroupID = $arItem["PROPERTIES"]["PARTICIPANT_SPAM_GROUP"]["VALUE"];

    if($participantGroupID)
    {
        $arFilter = array(
            "GROUPS_ID" => $participantGroupID,
        );

        $arParameters = array(
            "FIELDS" => array("ID", "LOGIN", "EMAIL", "WORK_COMPANY", "ACTIVE"),
            "SELECT" => array("UF_*")
            //UF_ID - ������ �����, UF_ID2 - ����, UF_ID3 - ����, UF_ID4 - ������, UF_ID5 - ������ �����,UF_ID11 - ��������� ������������� ������ ����� - 2015, UF_MR - ����� �� ����, UF_EV - ����� �� �����, UF_HB -Hosted Buyers
        );

        $rsUsers = CUser::GetList(($by="id"), ($order="asc"), $arFilter, $arParameters);

        while($arUsers = $rsUsers->Fetch())
        {
            $arItem["PARTICIPANT"]["SPAM"][$arUsers["ID"]] = $arUsers["ID"];
        }
    }

    //��������� ���������

    $arItem["MESSAGES"]["INBOX"] = CHLMFunctions::GetMessagesCount(2, $arItem["ID"] , 1, 3);//CForumPrivateMessage::GetListEx(array(), array("USER_ID"=> UID, "FOLDER_ID"=>FID_INBOX), true);
    $arItem["MESSAGES"]["SENT"] = CHLMFunctions::GetMessagesCount(2, $arItem["ID"] , 1, 4);///CForumPrivateMessage::GetListEx(array(), array("USER_ID"=> UID, "FOLDER_ID"=>FID_SENT), true);

    $arResult["ITEMS"][] = $arItem;
}

//����� �������
$this->IncludeComponentTemplate();