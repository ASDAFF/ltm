<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
switch ($arResult['USER_TYPE']) {
	case 'ADMIN':
		include_once(dirname(__FILE__) . '/particip.php');
		break;
	case 'PARTICIP':
		include_once(dirname(__FILE__) . '/particip.php');
		break;
	case 'GUEST':
		include_once(dirname(__FILE__) . '/guest.php');
}
?>
<script type="text/javascript">
	setTimeout( function() { window.close(); }, 5000);
</script>
	<script type='text/javascript'>top.opener.document.location.reload();</script>
<?
//var_dump($arResult);
?>