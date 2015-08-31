<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (!CModule::IncludeModule("highloadblock")):
	ShowError(GetMessage("HL_NO_MODULE"));
	return 0;
elseif (!$USER->IsAuthorized()):
	$APPLICATION->AuthForm(GetMessage("HLM_AUTH"));
	return 0;
endif;

// *****************************************************************************************

	InitSorting();
	global $by, $order;

/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
	$arParams["MID"] = intVal($arParams["MID"] > 0 ? $arParams["MID"] : $_REQUEST["MID"]);

	$arParams["UID"] = intVal(empty($arParams["UID"]) ? $_REQUEST["UID"] : $arParams["UID"]);
	$arParams["FID"] = intVal(empty($arParams["FID"]) ? $_REQUEST["FID"] : $arParams["FID"]);
	$arParams["EID"] = intVal(empty($arParams["EID"]) ? $_REQUEST["EID"] : $arParams["EID"]);
	$arParams["HLID"] = intVal(empty($arParams["HLID"]) ? $_REQUEST["HLID"] : $arParams["HLID"]);

/***************** STANDART ****************************************/
	$arParams["SET_NAVIGATION"] = ($arParams["SET_NAVIGATION"] == "Y" ? "Y" : "N");
	if ($arParams["CACHE_TYPE"] == "Y" || ($arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main", "component_cache_on", "Y") == "Y"))
		$arParams["CACHE_TIME"] = intval($arParams["CACHE_TIME"]);
	else
		$arParams["CACHE_TIME"] = 0;
	$arParams["SET_TITLE"] = ($arParams["SET_TITLE"] == "N" ? "N" : "Y");

/***************** URL *********************************************/
$URL_NAME_DEFAULT = array(
    "hlm_list" => "",
    "hlm_read" => "MID=#MID#",
    "hlm_new" => "id=#UID#",
    "hlm_company_view" => "",
);

foreach ($URL_NAME_DEFAULT as $URL => $URL_VALUE)
{
    if (strLen(trim($arParams["URL_TEMPLATES_".strToUpper($URL)])) <= 0)
        $arParams["URL_TEMPLATES_".strToUpper($URL)] = $APPLICATION->GetCurPageParam($URL_VALUE, array("FID", "TID", "UID", "MID", "action","sessid", BX_AJAX_PARAM_ID));
    $arParams["~URL_TEMPLATES_".strToUpper($URL)] = $arParams["URL_TEMPLATES_".strToUpper($URL)];

    $arParams["URL_TEMPLATES_".strToUpper($URL)] = htmlspecialcharsEx($arParams["~URL_TEMPLATES_".strToUpper($URL)]);
}

/********************************************************************
				/Input params
********************************************************************/

// начало ********************* highloadblock init ***************************************

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$hlblock = HL\HighloadBlockTable::getById($arParams["HLID"])->fetch();
// получаем сущность
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$HLDataClass = $entity->getDataClass();

// uf info
global $USER_FIELD_MANAGER;
$HLFields = $USER_FIELD_MANAGER->GetUserFields('HLBLOCK_'.$hlblock['ID'], 0, LANGUAGE_ID);


// конец ********************* highloadblock init *****************************************


$arResult = array();
$arResult["EXHIBIT"] = CHLMFunctions::GetExhibByID($arParams["EID"]);
$arResult["ERROR_MESSAGE"] = "";
$arResult["OK_MESSAGE"] = "";
/********************************************************************
 Action
********************************************************************/
$action = strToLower($_REQUEST["action"]);
$arError = array();
if ($_SERVER['REQUEST_METHOD']=="POST" && !empty($action))
{
	$APPLICATION->ResetException();

	if (!check_bitrix_sessid())
	{
		$arError[] = array("id" => "BAD_SESSID", "text" => GetMessage("HL_ERR_SESS_FINISH"));
	}
	elseif ($action == "send")
	{
		$USER_INFO = array();
		if(strLen($_REQUEST["RECIPIENT"])>0)
		{
			if (intVal($_REQUEST["RECIPIENT"]) > 0)
				$USER_INFO = CUser::GetByID($_REQUEST["RECIPIENT"]);
				$USER_INFO = $USER_INFO->Fetch();
			if (empty($USER_INFO))
				$USER_INFO = CUser::GetByLogin($_REQUEST["RECIPIENT"]);
		}
		if (empty($USER_INFO))
		{
			$arError[] = array("id" => "bad_user_info","text" => str_replace("##", htmlspecialcharsEx($_REQUEST["RECIPIENT"]), GetMessage("HLM_USER_NOT_FOUND")));
		}
		else
		{

			$arUserFieldsFolder = getPropertyEnum($HLFields["UF_FOLDER"]["ID"]);
			foreach ($arUserFieldsFolder as $arFolder)
			{
				${$arFolder["XML_ID"]} = $arFolder;

			}

			$arrVars = array(
					"UF_AUTHOR" => $USER->GetID(),
					"UF_RECIPIENT" => $USER_INFO["ID"],
					"UF_POST_DATE" => date("d.m.Y H:i:s"),
					"UF_SUBJECT" => htmlspecialcharsEx($_REQUEST["POST_SUBJECT"]),
					"UF_MESSAGE" => htmlspecialcharsEx($_REQUEST["POST_MESSAGE"]),
					"UF_IS_READ" => 0,
					"UF_FOLDER" => $INBOX["ID"],
					"UF_EXHIBITION" => $arParams["EID"],
					);

			if($USER->IsAdmin())
			{
				$arrVars["UF_AUTHOR"] = "1";
			}

			$result = $HLDataClass::Add($arrVars);
			if(isset($arParams["COPY_TO_OUTBOX"]) && $arParams["COPY_TO_OUTBOX"] == "Y")
			{
				$arrVars["UF_FOLDER"] = $SENT["ID"];
				$arrVars["UF_IS_READ"] = 1;
				$HLDataClass::Add($arrVars);
			}



			$resultMID = $result->getId();
			if (intVal($resultMID) <= 0)
			{
				$err = $APPLICATION->GetException();
				$arError[] = array("id" => "bad_send","text" => $err->GetString());
			}
			elseif ($arParams["SEND_EMAIL"] == "Y")
			{
			    //получаем данные пользователя из вебформы
			    $toUserData = CHLMFunctions::GetUserInfoForm($USER_INFO["ID"], $arResult["EXHIBIT"]);

				if (!empty($toUserData["EMAIL"])){
					$event = new CEvent;
					$arSiteInfo = $event->GetSiteFieldsArray(SITE_ID);

					$POST_MESSAGE = htmlspecialcharsEx($_REQUEST["POST_MESSAGE"]);
					if($USER->IsAdmin()){
						$arFields = Array(
								"FROM_NAME" => "Administrator LTM",
								"EXIB_SHORT" => $arResult["EXHIBIT"]["PROPERTY_SHORT_NAME_VALUE"],
								"FROM_USER_ID" => 1,
								"FROM_EMAIL" => $arSiteInfo["DEFAULT_EMAIL_FROM"],
								"TO_NAME" => $toUserData["NAME"]. " " . $toUserData["LAST_NAME"],
								"TO_USER_ID" => $USER_INFO["ID"],
								"TO_EMAIL" => $toUserData["EMAIL"],
								"SUBJECT" => $_REQUEST["POST_SUBJECT"],
								"MESSAGE" => $POST_MESSAGE,
								"MESSAGE_DATE" => date("d.m.Y H:i:s"),
								"MESSAGE_LINK" => "http://".SITE_SERVER_NAME.CComponentEngine::MakePathFromTemplate($arParams["~URL_TEMPLATES_PM_READ"],
										array("FID" => "INBOX", "MID" => $resultMID))
						);
						$templateMsg = "NEW_ADMIN_MESSAGE";
					}
					else{
					    $thisUserdata = CHLMFunctions::GetUserInfoForm($USER->GetID(), $arResult["EXHIBIT"]);
						$arFields = Array(
								"FROM_NAME" => $thisUserdata["NAME"]. " " . $thisUserdata["LAST_NAME"],
								"EXIB_SHORT" => $arResult["EXHIBIT"]["PROPERTY_SHORT_NAME_VALUE"],
								"FROM_USER_ID" => $USER->GetID(),
								"FROM_EMAIL" => $arSiteInfo["DEFAULT_EMAIL_FROM"],
								"TO_NAME" => $toUserData["NAME"]. " " . $toUserData["LAST_NAME"],
								"TO_USER_ID" => $USER_INFO["ID"],
								"TO_EMAIL" => $toUserData["EMAIL"],
								"SUBJECT" => $_REQUEST["POST_SUBJECT"],
								"MESSAGE" => $POST_MESSAGE,
								"MESSAGE_DATE" => date("d.m.Y H:i:s"),
								"MESSAGE_LINK" => "http://".SITE_SERVER_NAME.CComponentEngine::MakePathFromTemplate($arParams["~URL_TEMPLATES_PM_READ"],
										array("FID" => "INBOX", "MID" => $resultMID))
						);
						$templateMsg = "NEW_FORUM_PRIVATE_MESSAGE";
					}
					$event->Send($templateMsg, SITE_ID, $arFields, "N");
				};
			}
		}
	}

	if (empty($arError))
	{
		if ($action == "save")
		{
			$arResult["MESSAGE_SUCCESS"] = GetMessage("HLM_MESSAGE_SUCCESS");
			$arResult["SUBJECT_SUCCESS"] = $_REQUEST["POST_SUBJECT"];
			$arResult["TEXT_SUCCESS"] = $_REQUEST["POST_MESSAGE"];
		}
		elseif ($action == "send")
		{
			$arResult["MESSAGE_SUCCESS"] = GetMessage("HLM_MESSAGE_SUCCESS");
			$arResult["SUBJECT_SUCCESS"] = $_REQUEST["POST_SUBJECT"];
			$arResult["TEXT_SUCCESS"] = $_REQUEST["POST_MESSAGE"];
			$rsTmpUser = CUser::GetByID($arParams["UID"]);
			$arTmpUser = $rsTmpUser->Fetch();
			$toUserData = CHLMFunctions::GetUserInfoForm($arTmpUser["ID"], $arResult["EXHIBIT"]);
			$arFieldsMes = array(
					"EMAIL" => $toUserData["EMAIL"]
			);
			CEvent::Send("NEW_PERSONAL_MESSAGE","s1",$arFieldsMes);
		}
	}
	else
	{
		$e = new CAdminException(array_reverse($arError));
		$GLOBALS["APPLICATION"]->ThrowException($e);
		$err = $GLOBALS['APPLICATION']->GetException();
		$arResult["ERROR_MESSAGE"] = $err->GetString();
		$bVarsFromForm = true;
	}
}
/********************************************************************
 Action
********************************************************************/

/********************************************************************
				Data
********************************************************************/
$arResult["action"] = $mode=="edit" ? "save" : "send";

$arResult["sessid"] = bitrix_sessid_post();
$arResult["FID"] = intVal($arParams["FID"]);
$arResult["MID"] = intVal($arParams["MID"]);
$arResult["mode"] = $mode;
$arResult["SystemFolder"] = "TRASH";

// *****************************************************************************************
// Info about current user
$arResult["CurrUser"]["SHOW_NAME"] = (trim($USER->GetFullName()) <= 0 ? $USER->GetLogin() : $USER->GetFullName());

$arResult["FolderName"] = GetMessage("HLM_FOLDER_".$arParams["FID"]);
// *****************************************************************************************
$arResult["POST_VALUES"] = array();
if (!$bVarsFromForm && ($mode == "edit" || $mode=="reply"))
{
	if ($mode == "reply")
	{
		$arResult["POST_VALUES"]["POST_SUBJECT"] = GetMessage("HLM_REPLY").$arResult["SUBJECT"];
		$arResult["POST_VALUES"]["POST_MESSAGE"] = "[QUOTE]".$arResult["MESSAGE"]."[/QUOTE]";
		$arResult["POST_VALUES"]["USER_ID"] = $arResult["AUTHOR"];
		$arResult["POST_VALUES"]["USER_LOGIN"] = htmlspecialcharsEx(CHLMFunctions::GetUserName($arResult["RECIPIENT"]));
	}
}
elseif ($bVarsFromForm)
{
	$arResult["POST_VALUES"]["POST_SUBJECT"] = htmlspecialcharsEx($_REQUEST["POST_SUBJECT"]);
	$arResult["POST_VALUES"]["POST_MESSAGE"] = htmlspecialcharsEx($_REQUEST["POST_MESSAGE"]);
	$arResult["POST_VALUES"]["USER_ID"] = htmlspecialcharsEx($_REQUEST["USER_ID"]);
}
elseif ($arParams["UID"] > 0)
{
	$arResult["POST_VALUES"]["USER_ID"] = intVal($arParams["UID"]);
}

/********************************************************************
				/Data
********************************************************************/
if ($arParams["SET_NAVIGATION"] != "N")
{
	$APPLICATION->AddChainItem(GetMessage("HLM_TITLE_NAV"), CComponentEngine::MakePathFromTemplate($arParams["~URL_TEMPLATES_HLM_FOLDER"], array()));
	$APPLICATION->AddChainItem($arResult["MESSAGE"]["POST_SUBJECT"], CComponentEngine::MakePathFromTemplate($arParams["~URL_TEMPLATES_HLM_READ"],
			array("FID" => $arParams["FID"], "MID" => $arParams["MID"])));
}
/*******************************************************************/
if ($arParams["SET_TITLE"] != "N")
{
	$APPLICATION->SetTitle(GetMessage("HLM_TITLE_NEW"));
}
if($USER->IsAdmin()){
	$arResult["USERS"]["FROM"] = CHLMFunctions::GetUserName(1);
}
else{
    $thisUserdata = CHLMFunctions::GetUserInfoForm($USER->GetID(), $arResult["EXHIBIT"]);
	$arResult["USERS"]["FROM"] = $thisUserdata["NAME"]. " " .$thisUserdata["LAST_NAME"];
}
$toUserdata = CHLMFunctions::GetUserInfoForm($arParams["UID"], $arResult["EXHIBIT"]);
$arResult["USERS"]["TO"] = array("ID" => $arParams["UID"], "NAME" => $toUserdata["NAME"]. " " .$toUserdata["LAST_NAME"] . ". Company: " . $toUserdata["COMPANY_NAME"]);

if(1 == $arParams["UID"])
{
	$arResult["USERS"]["TO"] = array("ID" => $arParams["UID"], "NAME" => "Administration Company: LTM");
}

$arResult["USERS"]["TO_LIST"]["LIST"] = array();
$arResult["USERS"]["TO_LIST"]["COUNT"] = 0;
if(!$arParams["UID"] && isset($arParams["GROUP_WRITE"]) && $arParams["GROUP_WRITE"] != ''){
  $arSParams["SELECT"] = array("ID", "WORK_COMPANY");
  if($arParams["GROUP_TYPE"] == 'GUEST'){
	  $filter = array(
		  "GROUPS_ID"  => array($arParams["GROUP_WRITE"]),
		  "UF_MR" => "1"
	  );
  }
  else{
	  $filter = array(
		  "GROUPS_ID"  => array($arParams["GROUP_WRITE"])
	  );
  }
  $rsTUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, $arSParams); // выбираем пользователей
  while($arUsersTemp=$rsTUsers->Fetch()){
	$arResult["USERS"]["TO_LIST"]["LIST"][$arUsersTemp["ID"]] = $arUsersTemp["WORK_COMPANY"];
	$arResult["USERS"]["TO_LIST"]["COUNT"]++;
  }
}
$thisMessage = array();
$thisMessage["POST_SUBJECT"] = '';

if(isset($arParams["MID"]) && intval($arParams["MID"]) > 0){
    $main_query = new Entity\Query($entity);
    $main_query->setSelect(array('*'));
    $main_query->setFilter(array("=ID" => intval($arParams["MID"])));

    $rMessage = $main_query->exec();
    $rMessage = new CDBResult($rMessage);

	if ($rMessage && ($arMessage = $rMessage->Fetch()))
	{
	    if(!$arMessage["UF_IS_READ"]){
    	    $arMessage["UF_IS_READ"] =  1;
            $USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$hlblock['ID'], null, $arMessage);
            $result = $HLDataClass::Update($arMessage["ID"], $arMessage);

            if(!$result->isSuccess())
            {
                $arResult["ERROR_MESSAGE"][] = $result->getErrorMessages();
            }
	    }

		$thisMessage["POST_SUBJECT"] = "RE: ".$arMessage["UF_SUBJECT"];
	}

}
$arResult["MESS"]["SUBJECT"] = $thisMessage["POST_SUBJECT"];


$this->IncludeComponentTemplate();




function getPropertyEnum($id)
{
	$rsUserField = CUserTypeEnum::GetList(array("ID" => $id));
	while($arUserField = $rsUserField->GetNext())
	{
		$arUserFields[$arUserField["ID"]] = $arUserField;
	}

	return $arUserFields;
}
?>