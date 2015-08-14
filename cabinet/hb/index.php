<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div id="exhibition-tab-1">
<?
global $APPLICATION;
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/cabinet/" . $exhibCode . "/hb/";
$userId = trim($_REQUEST["UID"]);
CModule::IncludeModule("iblock");
$rsExhib = CIBlockElement::GetList(array(), array("IBLOCK_ID" => "15", "CODE" => $exhibCode, "ACTIVE" => "Y"), false, false, array("PROPERTY_APP_HB_ID"));
$arExhib = $rsExhib->Fetch();
if($arExhib["PROPERTY_APP_HB_ID_VALUE"] != ""){
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