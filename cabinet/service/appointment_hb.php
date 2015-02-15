<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Назначение встреч");
$authUser = $USER->GetID();
if(isset($_REQUEST["id"]) && $_REQUEST["id"]!='' && $authUser == 1){
	$curUser = $_REQUEST["id"];
}
else{
	$curUser = $authUser;
}
if(isset($_REQUEST["app"]) && $_REQUEST["app"]!=''){
	$appId = $_REQUEST["app"];
}
else{
	$appId=1;
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
	$userType = '';
}
?>

<?$APPLICATION->IncludeComponent(
	"doka:meetings.request.send",
	"",
	Array(
		"APP_ID" => $appId,
		"IS_HB" => "Y",
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>