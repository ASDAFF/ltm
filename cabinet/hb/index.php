<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div id="exhibition-tab-1">
<?
global $APPLICATION;
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/cabinet/" . $exhibCode . "/hb/";
$userId = trim($_REQUEST["UID"]);
if($exhibCode == 'moscow-russia-march-12-2015'){
	$newURL = "/cabinet/" .$exhibCode . "/hb/schedule/". (($userId)?"?UID=" . $userId : "");
	LocalRedirect($newURL);
}
else{
	if($_SESSION["USER_TYPE"] == "PARTICIPANT")
	{?>
		<div class="morning-session" id="morning-session">
			<? include("hb(" . $exhibCode . ").php");?>
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
}
?>
</div>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>