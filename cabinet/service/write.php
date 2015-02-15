<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Write a message");


$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/cabinet/" . $exhibCode . "/messages/";

$arExhib = CHLMFunctions::GetExhibByCode($exhibCode);
$exhibID = $arExhib["ID"];
$exhibPGroup =  $arExhib["PROPERTY_USER_GROUP_ID_VALUE"];
$exhibGGroup =  $arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"];

?>
<div class="message-box">
<div class="new-message">
<?
if($_SESSION["USER_TYPE"] == "PARTICIPANT"){
$APPLICATION->IncludeComponent(
	"rarus:messages.new",
	"",
	Array(
		"HLID" => "2",
		"EID" => $exhibID,
		"COPY_TO_OUTBOX" => "Y",
		"SEND_EMAIL" => "Y",
		"MID" => $_REQUEST["mes"],
	    "UID" => $_REQUEST["id"],
	    "SET_TITLE" => "N",
        "URL_TEMPLATES_HLM_LIST" => "/cabinet/".$exhibCode."/messages/#FCODE#/",
        "URL_TEMPLATES_HLM_READ" => "/cabinet/".$exhibCode."/messages/read/?MID=#MID#",
        "URL_TEMPLATES_HLM_NEW" => "/cabinet/".$exhibCode."/messages/new/?id=#UID#",
        "URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",
	    "GROUP_WRITE" => $exhibGGroup,
	    "GROUP_TYPE" => "GUEST",

	),
false
);
}
else{
	$APPLICATION->IncludeComponent(
	"rarus:messages.new",
	"",
	Array(
		"HLID" => "2",
		"EID" => $exhibID,
		"COPY_TO_OUTBOX" => "Y",
		"SEND_EMAIL" => "Y",
		"MID" => $_REQUEST["mes"],
	    "UID" => $_REQUEST["id"],
	    "SET_TITLE" => "N",
        "URL_TEMPLATES_HLM_LIST" => "/cabinet/".$exhibCode."/messages/#FCODE#/",
        "URL_TEMPLATES_HLM_READ" => "/cabinet/".$exhibCode."/messages/read/?MID=#MID#",
        "URL_TEMPLATES_HLM_NEW" => "/cabinet/".$exhibCode."/messages/new/?id=#UID#",
        "URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",
	    "GROUP_WRITE" => $exhibPGroup,
	    //"GROUP_TYPE" => "GUEST",

	),
false
);
}
?>
</div>
</div>
<?

/*
if($_SESSION["USER_TYPE"] == "PARTICIPANT"){
	$APPLICATION->IncludeComponent(
	"btm:forum.pm.edit",
	"write_particip",
	Array(
		"MID" => $_REQUEST["MID"],
		"FID" => $_REQUEST["FID"],
		"UID" => $_REQUEST["id"],
		"mode" => $_REQUEST["mode"],
		"URL_TEMPLATES_PM_LIST" => "pm_list.php?FID=#FID#",
		"URL_TEMPLATES_PM_READ" => "pm_read.php?MID=#MID#",
		"URL_TEMPLATES_PM_EDIT" => "pm_edit.php?MID=#MID#",
		"URL_TEMPLATES_PM_SEARCH" => "pm_search.php?MID=#MID#",
		"URL_TEMPLATES_PROFILE_VIEW" => "profile_view.php?UID=#UID#",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"SET_NAVIGATION" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"SET_TITLE" => "Y"
	),
false
);
}
else{
	$APPLICATION->IncludeComponent(
	"btm:forum.pm.edit",
	"write_guest",
	Array(
		"MID" => $_REQUEST["MID"],
		"FID" => $_REQUEST["FID"],
		"UID" => $_REQUEST["id"],
		"mode" => $_REQUEST["mode"],
		"URL_TEMPLATES_PM_LIST" => "pm_list.php?FID=#FID#",
		"URL_TEMPLATES_PM_READ" => "pm_read.php?MID=#MID#",
		"URL_TEMPLATES_PM_EDIT" => "pm_edit.php?MID=#MID#",
		"URL_TEMPLATES_PM_SEARCH" => "pm_search.php?MID=#MID#",
		"URL_TEMPLATES_PROFILE_VIEW" => "profile_view.php?UID=#UID#",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"SET_NAVIGATION" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"SET_TITLE" => "Y"
	),
false
);
}
*/
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>