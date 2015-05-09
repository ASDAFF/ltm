<?
$login = strip_tags($_REQUEST['login']);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
if(CModule::IncludeModule("iblock")){
	$login = $login;
	$filter = Array("LOGIN" => $login);
	$rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), $filter);
	$count = 0;
	while($rsUsers->NavNext(true, "f_")){
		$count++;
	};
	if($login == ''){
		$count = 0;
	}
	echo $count;
}
?>