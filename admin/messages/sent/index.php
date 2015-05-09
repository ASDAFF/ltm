<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Sent");
?>
<?
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/admin/" . $exhibCode . "/messages/";

$arExhib = CHLMFunctions::GetExhibByCode($exhibCode);
$exhibID = $arExhib["ID"];
$exhibPGroup =  $arExhib["PROPERTY_USER_GROUP_ID_VALUE"];
$exhibGGroup =  $arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"];
?>

<div class="menu">
    <ul>
        <li><a href="<?= $page?>inbox/" class="custom-buttom">Входящие</a></li>
        <li class="active"><a href="<?= $page?>sent/" class="custom-buttom" >Исходящие</a></li>
        <li><a href="<?= $page?>new/" class="custom-buttom">Написать</a></li>
    </ul>
</div>
<?
$APPLICATION->IncludeComponent(
	"rarus:messages.list",
	"admin_sent",
	Array(
		"HLID" => "2",
		"EID" => $exhibID,
		"FID" => "4",
	    "SET_TITLE" => "N",
		"PM_PER_PAGE" => "20",
		"DATE_FORMAT" => "d.m.Y",
		"DATE_TIME_FORMAT" => "H:i:s",
        "URL_TEMPLATES_HLM_LIST" => "/admin/".$exhibCode."/messages/#FCODE#/",
        "URL_TEMPLATES_HLM_READ" => "/admin/service/read.php?MID=#MID#",
        "URL_TEMPLATES_HLM_NEW" => "/admin/service/write.php?id=#UID#",
        "URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",

	),
false
);?>

<?/*
$APPLICATION->IncludeComponent(
	"btm:forum.pm.list",
	"admin_sent",
	Array(
		"FID" => "2",
		"URL_TEMPLATES_PM_LIST" => "pm_list.php?FID=#FID#",
		"URL_TEMPLATES_PM_READ" => "pm_read.php?MID=#MID#",
		"URL_TEMPLATES_PM_EDIT" => "pm_edit.php?MID=#MID#&mode=#mode#",
		"URL_TEMPLATES_PM_FOLDER" => "pm_folder.php",
		"URL_TEMPLATES_PROFILE_VIEW" => "profile_view.php?UID=#UID#",
		"PAGE_NAVIGATION_TEMPLATE" => "",
		"PM_PER_PAGE" => "20",
		"DATE_FORMAT" => "d.m.Y",
		"DATE_TIME_FORMAT" => "d.m.Y H:i:s",
		"SET_NAVIGATION" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"CACHE_NOTES" => "",
		"SET_TITLE" => "N"
	)
);
*/
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>