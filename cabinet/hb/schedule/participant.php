<?
$authUser = $USER->GetID();
if(isset($_REQUEST["UID"]) && $_REQUEST["UID"]!='' && $authUser == 1){
	$curUser = $_REQUEST["UID"];
}
else{
	$curUser = $authUser;
}
if(isset($_REQUEST["type"]) && $_REQUEST["type"]!='' && $authUser == 1){
	if($_REQUEST["type"] == "p"){
		$userType = "PARTICIP";
	}
	else{
		$userType = "GUEST";
	}
}
else{
	$userType = 'PARTICIP';
}
$APPLICATION->IncludeComponent(
	"doka:meetings.schedule",
	"",
	Array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"EXHIB_IBLOCK_ID" => "15",
		"EXIB_CODE" => $exhibCode,
		"USER_TYPE" => $userType,
		"USER_ID" => $curUser,
		"MESSAGE_LINK" => "/service/write.php",
		"SEND_REQUEST_LINK" => "/service/appointment_hb.php",
		"CONFIRM_REQUEST_LINK" => "/service/appointment_hb_confirm.php",
		"REJECT_REQUEST_LINK" => "/service/appointment_hb_del.php",
		"CUT" => "9",
		"HALL" => "10",
		"TABLE" => "10",
		"IS_HB" => "Y",
	),
false
);?>
<div class="request-guests">
<?$APPLICATION->IncludeComponent(
	"doka:meetings.wishlist",
	"",
	Array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"EXHIB_IBLOCK_ID" => "15",
		"EXIB_CODE" => $exhibCode,
		"USER_TYPE" => $userType,
		"USER_ID" => $curUser,
		"MESSAGE_LINK" => "/cabinet/service/write.php",
		"IS_HB" => "Y",
	),
false
);?>
</div>
