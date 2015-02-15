<?
$login = strip_tags($_REQUEST['login']);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");

function strcode($str, $passw=""){
		$salt = "Dn8*#2n!9j";
		$len = strlen($str);
		$gamma = '';
		$n = $len>100 ? 8 : 2;
		while( strlen($gamma)<$len ){
			$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
		}
		return $str^$gamma;
	}
//Проверяем, есть ли вообще введённый пароль в базе
$filter = Array("!UF_PAS" => "", 'LOGIN'=>$login); 
$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_PAS")));
while ($arUser = $rsUsers->Fetch()){
	$pas = $arUser['UF_PAS'];
	$mail = $arUser['EMAIL'];
}
if($pas !=''){
	$depas = strcode(base64_decode($pas), 'luxoran');
	$arEventFields = array(
		"PASSWORD"         => $depas,
		"MAIL"             => $mail
		);
	CEvent::Send("REMIND_PAS", 's1', $arEventFields);
}else{
	echo 'ER_LOGIN';
}
?>