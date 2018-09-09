<?
if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<? if ($arResult['REQUEST_REJECTED']): ?>
    <?
    switch ($arResult['USER_TYPE_NAME']) {
        case 'ADMIN':
            include_once(dirname(__FILE__).'/particip.php');
            if ( !empty($arParams['RELOAD']) && $arParams['RELOAD'] == 'N') {
                echo "<p>You have to reload the parent page yourself</p>";
            }
            break;
        case 'PARTICIP':
            include_once(dirname(__FILE__).'/particip.php');
            break;
        case 'GUEST':
            include_once(dirname(__FILE__).'/guest.php');
    }
    ?>
    <script type="text/javascript">
		setTimeout(function () {
			window.close();
		}, 5000);
    </script>
    <? if (empty($arParams['RELOAD']) || $arParams['RELOAD'] != 'N'): ?>
        <script type='text/javascript'>top.opener.document.location.reload();</script>
    <? endif; ?>
<? endif; ?>
