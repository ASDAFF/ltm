<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="modal-window ">

</div>
<?
switch ($arResult['USER_TYPE']) {
	case 'ADMIN':
	case 'PARTICIP':
		include_once(dirname(__FILE__) . '/particip.php');
		break;
	case 'GUEST':
		include_once(dirname(__FILE__) . '/guest.php');
}
?>