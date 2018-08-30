<?define("NEED_AUTH", true);?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?
define("MAX_PHOTO_COUNT", 12);
global $USER;
if($USER->IsAdmin() && isset($_REQUEST["UID"])) {
	$userId = intval($_REQUEST["UID"]);
} else {
	$userId = $USER->GetID();
}
$rsUser = CUser::GetList(($by = false), ($order = false), array("ID"=>$userId), array("SELECT"=>array("UF_*")));
$arUser = $rsUser->Fetch();
$galleryID = $arUser["UF_ID_GROUP"];

$APPLICATION->IncludeComponent(
		"rarus:members.photoloader",
		"",
		Array(
			"IBLOCK_ID" => "16",
			"GALLERY_ID" => $galleryID,
			"USER_ID" => $userId,
			"MAX_PHOTO_COUNT" => MAX_PHOTO_COUNT,
			"MAX_FILE_SIZE" => 0,
			"CACHE_TIME" => "3600"
		),
		false
	); ?>

<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>