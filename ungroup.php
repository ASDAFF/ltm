<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
?>
<?

define("DELETE_GROUP_ID", 6);

if(!$USER->IsAdmin())
{
	LocalRedirect("/");
}

$arFilter = array(
	"ADMIN" => "N",
    "GROUPS_ID" => array(DELETE_GROUP_ID),
);

$rsUsers = $USER->GetList(($by="id"), ($order="desc"), $arFilter);
while($arUsers = $rsUsers->Fetch())
{
    pre($arUsers["LOGIN"]);
    //все группы пользователя
	$arGroups = CUser::GetUserGroup($arUsers["ID"]);
	$indexGroup = array_search(DELETE_GROUP_ID, $arGroups);

	if($indexGroup !== false)
	{
		unset($arGroups[$indexGroup]);
		$USER->SetUserGroup($arUsers["ID"], $arGroups);
	}


}

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>