<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div id="exhibition-tab-1">

<?
$userId = $_REQUEST["UID"];
$exhibCode = $_REQUEST["EXHIBIT_CODE"];
if($exhibCode)
{
	$newURL = "/cabinet/" .$exhibCode . "/morning/schedule/". (($userId)?"?UID=" . $userId : "");
	LocalRedirect($newURL);
}
else
{
	echo "<p>Unknown exhibition</p>";
}
?>
</div>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>