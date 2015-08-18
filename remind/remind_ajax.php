<?
$login = strip_tags($_REQUEST['login']);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");

//Проверяем, есть ли вообще введённый пароль в базе
$filter = Array("!UF_PAS" => "", 'LOGIN'=>$login); 
$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_PAS")));
while ($arUser = $rsUsers->Fetch()){
	$pas = $arUser['UF_PAS'];
	$mail = $arUser['EMAIL'];
}
if($pas !=''){
	$depas = makePassDeCode($pas);
	$arEventFields = array(
		"PASSWORD"         => $depas,
		"MAIL"             => $mail
		);
	CEvent::Send("REMIND_PAS", 's1', $arEventFields);
}else{
	echo 'ER_LOGIN';
}
?>