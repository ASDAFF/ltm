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
	$newURL = "/admin/" .$exhibCode . "/messages/inbox/";
	LocalRedirect($newURL);
}
else
{
	echo "<p>Unknown exhibition</p>";
}
?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>