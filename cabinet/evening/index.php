<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div id="exhibition-tab-1" class="evening-session">
<?
global $APPLICATION;
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/cabinet/" . $exhibCode . "/morning/";
$userId = trim($_REQUEST["UID"]);

if($_SESSION["USER_TYPE"] == "PARTICIPANT")
{?>
	<div class="morning-session" id="morning-session">
		<? include("participant.php");?>
	</div>
<?
}
elseif($_SESSION["USER_TYPE"] == "GUEST")
{?>
  	<div class="morning-session" id="morning-session">
		<?// include("guest.php");?>
	</div>
<?
}
?>
</div>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>