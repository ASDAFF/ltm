<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div id="exhibition-tab-1">

<?
if($_SESSION["USER_TYPE"] == "PARTICIPANT")
{
	include("participant.php");
}
elseif($_SESSION["USER_TYPE"] == "GUEST")
{
    include("guest.php");
}
?>
</div>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>