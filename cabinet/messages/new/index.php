<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//языковой файл
include(GetLangFileName(dirname(dirname(__FILE__)).'/lang/', '/index.php'));
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
/*
if($exhibCode != "baku-azerbaijan-april-10-2014")
{
	echo GetMessage("MESSAGES_BLOCKED");
	$close = true;
}
if($USER->GetLogin() == "test2_partc"):
    $close=false;
endif
*/
?>

<? if(!$close):?>
	<?//--> Список табов 2 уровня ?>
    <ul class="message-list-tab pull-overflow">
        <li><a href="<?= $page . "inbox/" . (($userId)?"?UID=".$userId:"")?>" title="<?=GetMessage("MESSAGES_INBOX")?>"><?=GetMessage("MESSAGES_INBOX")?></a></li>
        <li><a href="<?= $page . "sent/" . (($userId)?"?UID=".$userId:"")?>" title="<?=GetMessage("MESSAGES_SENT")?>"><?=GetMessage("MESSAGES_SENT")?></a></li>
        <li class="ui-tabs-active"><a href="<?= $page . "new/" . (($userId)?"?UID=".$userId:"")?>" title="<?=GetMessage("MESSAGES_NEW")?>"><?=GetMessage("MESSAGES_NEW")?></a></li>
        <li><a href="<?= $page . "contact/" . (($userId)?"?UID=".$userId:"")?>" title="<?=GetMessage("MESSAGES_CONTACT")?>"><?=GetMessage("MESSAGES_CONTACT")?></a></li>
    </ul>
		<div id="message-tab-3" class="new-message">
		<? if($_SESSION["USER_TYPE"] == "PARTICIPANT")
		{
		    include("participant.php");
		}
		elseif($_SESSION["USER_TYPE"] == "GUEST")
		{
		    include("guest.php");
		}?>
		</div>
<? endif;?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>