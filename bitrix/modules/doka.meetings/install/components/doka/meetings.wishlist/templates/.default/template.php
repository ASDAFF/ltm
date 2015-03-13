<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
switch ($arResult['USER_TYPE']) {
	case 'PARTICIP':
		include_once(dirname(__FILE__) . '/particip.php');
		break;
	case 'GUEST':
		include_once(dirname(__FILE__) . '/' . strtolower($arResult['USER_TYPE']));
}
var_dump($arResult);
?>
