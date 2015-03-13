<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->AddHeadString('<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>',true);
switch ($arResult['USER_TYPE']) {
	case 'PARTICIP':
		include_once(dirname(__FILE__) . '/particip.php');
		break;
	case 'GUEST':
		include_once(dirname(__FILE__) . '/' . strtolower($arResult['USER_TYPE']));
}
var_dump($arResult);
?>

<script>
	$(document).ready(function() {
    	// $('#results').fixedHeaderTable({ 
    	// 	footer: false, 
    	// 	cloneHeadToFoot: true, 
    	// 	altClass: 'odd', 
    	// 	autoShow: true, 
    	// 	fixedColumns: 1, 
    	// 	height: 400
    	// });
	});
</script>
