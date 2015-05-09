<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Contact the administrator");
?>
<div id="exhibition-tab-5" class="message-box">
    <div id="message-box-function">
<?
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/cabinet/" . $exhibCode . "/messages2/";
$userId = trim($_REQUEST["UID"]);
$close = false;
if($exhibCode != "moscow-russia-march-13-2014")
{
	echo "Messages blocked by the organizers";
	$close = true;
}

if($_SESSION["USER_TYPE"] == "PARTICIPANT" && !$close)
{?>
	<?//--> Список табов 2 уровня ?>
    <ul class="message-list-tab pull-overflow">
        <li><a href="<?= $page . "inbox/" . (($userId)?"?UID=".$userId:"")?>" title="Inbox">Inbox</a></li>
        <li><a href="<?= $page . "sent/" . (($userId)?"?UID=".$userId:"")?>" title="Sent">Sent</a></li>
        <li><a href="<?= $page . "new/" . (($userId)?"?UID=".$userId:"")?>" title="New message">New message</a></li>
        <li class="ui-tabs-active"><a href="<?= $page . "contact/" . (($userId)?"?UID=".$userId:"")?>" title="Contact organizers">Contact organizers</a></li>
    </ul>
		<div id="message-tab-3" class="new-message">
		<? include("participant.php");?>
		</div>
<?
}
elseif($_SESSION["USER_TYPE"] == "GUEST" && !$close)
{?>
	<?//--> Список табов 2 уровня ?>
    <ul class="message-list-tab pull-overflow">
        <li><a href="<?= $page . "inbox/" . (($userId)?"?UID=".$userId:"")?>" title="Входящие">Входящие</a></li>
        <li><a href="<?= $page . "sent/" . (($userId)?"?UID=".$userId:"")?>" title="Отправленные">Отправленные</a></li>
        <li><a href="<?= $page . "new/" . (($userId)?"?UID=".$userId:"")?>" title="Написать письмо">Написать письмо</a></li>
        <li class="ui-tabs-active"><a href="<?= $page . "contact/" . (($userId)?"?UID=".$userId:"")?>" title="Связаться с организаторами">Связаться с организаторами</a></li>
    </ul>
		<div id="message-tab-3" class="new-message">
		<? include("guest.php");?>
		</div>
<?
}
?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>