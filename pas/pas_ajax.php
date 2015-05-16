<?
$pas = strip_tags($_REQUEST['pas']);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");

function strcode($str, $passw=""){
		$salt = "Dn8*#2n!9j";
		$len = strlen($str);
		$gamma = '';
		$n = $len>100 ? 16 : 4;
		while( strlen($gamma)<$len ){
			$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
		}
		return $str^$gamma;
	}
$depas = strcode(base64_decode($pas), "luxoran");

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