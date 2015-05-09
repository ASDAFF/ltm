<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div id="exhibition-tab-1">
<?
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);

if($_SESSION["USER_TYPE"] == "PARTICIPANT")
{
	include("participant(". $exhibCode . ").php");
}
elseif($_SESSION["USER_TYPE"] == "GUEST")
{
    include("guest(" . $exhibCode . ").php");
}
?>
</div>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>