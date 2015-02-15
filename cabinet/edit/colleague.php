<?define("NEED_AUTH");?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div id="exhibition-tab-1">
<?
if($_SESSION["USER_TYPE"] == "PARTICIPANT")
{
	require("participant-colleague.php");
}
elseif($_SESSION["USER_TYPE"] == "GUEST")
{
    require("guest-colleague.php");
}
?>
</div><!-- exhibition-tab -->
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>