<?define("NEED_AUTH");?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div id="exhibition-tab-1">
<?
if($_SESSION["USER_TYPE"] == "PARTICIPANT")
{
	require("participant-profile.php");
}
elseif($_SESSION["USER_TYPE"] == "GUEST")
{
    require("guest-profile.php");
}
?>
</div>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>