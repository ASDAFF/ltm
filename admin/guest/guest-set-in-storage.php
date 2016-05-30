<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$obGS = new CLTMGuestStorage();
if(isset($_REQUEST["USER_ID"])) {
	$obGS->putInStorage($_REQUEST["USER_ID"]);
} elseif(isset($_REQUEST["SELECTED_USERS"])) {
	foreach($_REQUEST["SELECTED_USERS"] as $userId) {
		$obGS->putInStorage($userId);
	}
}