<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Виш лист");
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
?>
<? //$APPLICATION->IncludeComponent(
//	"doka:meetings.wishlist.add",
//	"",
//	Array(
//		"CACHE_TYPE" => "A",
//		"CACHE_TIME" => "3600",
//		"APP_ID" => $_REQUEST["app"],
//		"USER_ID" => $authUser,
//        "TO" => $_REQUEST["to"],
//		"EXIB_CODE" => $_REQUEST["exhib"],
//		"MESSAGE_LINK" => "/ru/personal/service/write.php"
//	),
//false
//);?>
<?
$APPLICATION->IncludeComponent('ds:meetings.wishlist.add',
    '',
    [
        'EXHIBITION_ID' => $_REQUEST["app"],
        'SENDER_ID' => $authUser,
        'RECEIVER_ID' => $_REQUEST["to"],
    ])
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>