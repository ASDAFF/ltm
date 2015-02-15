<?define("NEED_AUTH", true);?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?

$userId = $_REQUEST["UID"];
$exhibCode = $_REQUEST["EXHIBIT_CODE"];
if($exhibCode)
{
	$newURL = "/cabinet/" .$exhibCode . "/deadline/". (($userId)?"?UID=" . $userId : "");
	LocalRedirect($newURL);
}
else
{
	echo "<p>Unknown exhibition</p>";
}
?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
