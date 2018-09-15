<?
if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

?>
<? if ($arResult['APP_SETTINGS']['IS_LOCKED'] && $arResult['USER_TYPE_NAME'] !== 'ADMIN'): ?>
    <? echo Loc::getMessage('EXHIBITION_BLOCKED'); ?>
<? elseif (isset($arResult['REQUEST_SENT'])): ?>
    <? if ($arResult['REQUEST_SENT'] === false) {
        switch ($arResult['USER_TYPE_NAME']) {
            case 'ADMIN':
            case 'PARTICIPANT':
                echo "<p><b>Error sending request. Time slot is not available.</b></p> <p>Window will close after 5 sec.</p>";
                break;
            case 'GUEST':
                echo "<p><b>Ошибка отправки. Слот занят.</b></p><p> Закрытие через 5 секунд.</p>";
                break;
        }
    } else {
        switch ($arResult['USER_TYPE_NAME']) {
            case 'ADMIN':
            case 'PARTICIPANT':
                echo "<p>Request sent. Window will close after 5 sec.</p>";
                if ( !empty($arParams["RELOAD"]) && $arParams["RELOAD"] == 'N') {
                    echo "<p>You have to reload the parent page yourself</p>";
                }
                break;
            case 'GUEST':
                echo "<p>Запрос успешно отправлен. Закрытие через 5 секунд.</p>";
                break;
        }
    } ?>
    <script type="text/javascript">
		setTimeout(function () {
			window.close();
		}, 5000);
    </script>
    <? if (empty($arParams["RELOAD"]) || $arParams["RELOAD"] != 'N'): ?>
        <script type='text/javascript'>top.opener.document.location.reload();</script>
    <? endif; ?>
<? else: ?>
    <?
    switch ($arResult['USER_TYPE_NAME']) {
        case 'ADMIN':
            include_once(dirname(__FILE__).'/participant.php');
            break;
        case 'PARTICIPANT':
            include_once(dirname(__FILE__).'/participant.php');
            break;
        case 'GUEST':
            include_once(dirname(__FILE__).'/guest.php');
    }
    ?>
<? endif; ?>