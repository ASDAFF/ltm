<?
if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<? if (isset($arResult['ERROR_MESSAGE'])): ?>
    <? echo implode('<br>', $arResult['ERROR_MESSAGE']); ?>
<? else: ?>
    <? if ($arResult['TO_RESERVE']) { ?>
        <p>Selected timeslot will be reserved for your personal purposes and won't be available for buyers to request an
            appointment until you release it. Window will be closed after 5 sec.</p>
    <? } else {
        ?><p>You're releasing this timeslot and it can be used for an appointment request. Window will close after 5 sec.</p>
    <? } ?>
    <script type="text/javascript">
		setTimeout(function () {
			window.close();
		}, 5000);
    </script>
<? endif; ?>