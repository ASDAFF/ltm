<?
$pas = strip_tags($_REQUEST['pas']);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");

$depas = makePassDeCode($pas);

//Проверяем, есть ли вообще введённый пароль в базе
$filter = Array("!UF_PAS" => ""); 
$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_PAS")));
while ($arUser = $rsUsers->Fetch()){
	$pasAr[] = $arUser['UF_PAS'];
}
if(in_array($pas, $pasAr)){
	if($depas != ''){
		echo $depas;
	}
}else{
	echo $depas.'**********';
}
?>