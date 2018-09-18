<?
if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<? if ($arResult['REQUEST_CONFIRMED']): ?>
    <?
    switch ($arResult['USER_TYPE_NAME']) {
        case 'ADMIN':
            include_once(dirname(__FILE__).'/admin.php');
            break;
        case 'PARTICIPANT':
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
    <? if ($arParams['NEED_RELOAD']): ?>
        <script type='text/javascript'>top.opener.document.location.reload();</script>
    <? endif; ?>
<? endif; ?>
