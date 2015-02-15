<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Messages");
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
	<?//--> ������ ����� 2 ������ ?>
    <ul class="message-list-tab pull-overflow">
        <li class="ui-tabs-active"><a href="<?= $page . "inbox/" . (($userId)?"?UID=".$userId:"")?>" title="Inbox">Inbox</a></li>
        <li><a href="<?= $page . "sent/" . (($userId)?"?UID=".$userId:"")?>" title="Sent">Sent</a></li>
        <li><a href="<?= $page . "new/" . (($userId)?"?UID=".$userId:"")?>" title="New message">New message</a></li>
        <li><a href="<?= $page . "contact/" . (($userId)?"?UID=".$userId:"")?>" title="Contact organizers">Contact organizers</a></li>
    </ul>
		<div id="message-tab-1" class="inbox morning-session">
		<? include("participant.php");?>
		</div>
<?
}
elseif($_SESSION["USER_TYPE"] == "GUEST" && !$close)
{?>
	<?//--> ������ ����� 2 ������ ?>
    <ul class="message-list-tab pull-overflow">
        <li class="ui-tabs-active"><a href="<?= $page . "inbox/" . (($userId)?"?UID=".$userId:"")?>" title="��������">��������</a></li>
        <li><a href="<?= $page . "sent/" . (($userId)?"?UID=".$userId:"")?>" title="������������">������������</a></li>
        <li><a href="<?= $page . "new/" . (($userId)?"?UID=".$userId:"")?>" title="�������� ������">�������� ������</a></li>
        <li><a href="<?= $page . "contact/" . (($userId)?"?UID=".$userId:"")?>" title="��������� � ��������������">��������� � ��������������</a></li>
    </ul>
		<div id="message-tab-1" class="inbox morning-session">
		<? include("guest.php");?>
		</div>
<?
}
?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>