<?
/*
$PAROL_ZASHIFR - ЗАШИФРОВАННЫЙ ПАРОЛЬ
$txt - РАСШИФРОВАННЫЙ ПАРОЛЬ
*/
$PAROL_ZASHIFR;
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
$txt =  makePassDeCode($PAROL_ZASHIFR);
echo $txt;
?>