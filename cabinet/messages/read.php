<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//языковой файл
include(GetLangFileName(dirname(__FILE__).'/lang/', '/index.php'));
?>
<div id="exhibition-tab-5" class="message-box">
    <div id="message-box-function">
<?
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/cabinet/" . $exhibCode . "/messages/";

$arExhib = CHLMFunctions::GetExhibByCode($exhibCode);
$exhibID = $arExhib["ID"];
$exhibPGroup =  $arExhib["PROPERTY_USER_GROUP_ID_VALUE"];
$exhibGGroup =  $arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"];

$userId = trim($_REQUEST["UID"]);
$close = false;
//if($exhibCode != "moscow-russia-march-13-2014")
if($exhibCode != "baku-azerbaijan-april-10-2014")
{
	echo GetMessage("MESSAGES_BLOCKED");
	$close = true;
}
if($USER->GetLogin() == "test2_partc"):
    $close=false;
endif?>

<? if(!$close):?>
	<?//--> Список табов 2 уровня ?>
    <ul class="message-list-tab pull-overflow">
        <li class="ui-tabs-active"><a href="<?= $page . "inbox/" . (($userId)?"?UID=".$userId:"")?>" title="<?=GetMessage("MESSAGES_INBOX")?>"><?=GetMessage("MESSAGES_INBOX")?></a></li>
        <li><a href="<?= $page . "sent/" . (($userId)?"?UID=".$userId:"")?>" title="<?=GetMessage("MESSAGES_SENT")?>"><?=GetMessage("MESSAGES_SENT")?></a></li>
        <li><a href="<?= $page . "new/" . (($userId)?"?UID=".$userId:"")?>" title="<?=GetMessage("MESSAGES_NEW")?>"><?=GetMessage("MESSAGES_NEW")?></a></li>
        <li><a href="<?= $page . "contact/" . (($userId)?"?UID=".$userId:"")?>" title="<?=GetMessage("MESSAGES_CONTACT")?>"><?=GetMessage("MESSAGES_CONTACT")?></a></li>
    </ul>
		<div id="message-tab-1" class="inbox morning-session">
		<?
            $APPLICATION->IncludeComponent(
            	"rarus:messages.read",
            	"",
            	Array(
            		"SET_TITLE" => "Y",
            		"SET_NAVIGATION" => "Y",
                    "URL_TEMPLATES_HLM_LIST" => "/cabinet/".$exhibCode."/messages/#FCODE#/",
                    "URL_TEMPLATES_HLM_READ" => "/cabinet/".$exhibCode."/messages/read/?MID=#MID#",
                    "URL_TEMPLATES_HLM_NEW" => "/cabinet/".$exhibCode."/messages/new/?id=#UID#",
                    "URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",
                    "DATE_FORMAT" => "Y, dM",
                    "DATE_TIME_FORMAT" => "g:i A",
            		"MID" => $_REQUEST["mes"],
                    "HLID" => "2",
            	    "EID" => $exhibID,
            	),
            false
            );?>
		</div>
<? endif;?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

<?
/*
$APPLICATION->IncludeComponent(
	"btm:forum.pm.read",
	"read",
	Array(
		"SET_TITLE" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"SET_NAVIGATION" => "Y",
		"URL_TEMPLATES_PM_LIST" => "pm_list.php?FID=#FID#",
		"URL_TEMPLATES_PM_READ" => "read.php?mes=#MID#",
		"URL_TEMPLATES_PM_EDIT" => "pm_edit.php?MID=#MID#",
		"URL_TEMPLATES_PROFILE_VIEW" => "profile_view.php?UID=#UID#",
		"FID" => $_REQUEST["FID"],
		"MID" => $_REQUEST["mes"],
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/"
	),
false
);
*/
?>