<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Messages");
?>
<div id="exhibition-tab-5" class="message-box">
<?
$userId = $_REQUEST["UID"];
$exhibCode = $_REQUEST["EXHIBIT_CODE"];
if($exhibCode)
{
	$newURL = "/cabinet/" .$exhibCode . "/messages2/inbox/". (($userId)?"?UID=" . $userId : "");
	LocalRedirect($newURL);
}
else
{
	echo "<p>Unknown exhibition</p>";
}
?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>