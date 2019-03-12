<?
if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$APPLICATION->AddHeadString(
    '<script type="text/javascript" src="'.SITE_TEMPLATE_PATH.'/js/fixedheadertable.min.js"></script>',
    true
); ?>
<?
$userTypeName = strtolower($arResult['OLD_USER_TYPE_NAME']);
$app          = $arResult['APP_SETTINGS']['CODE'];
$hb           = strtolower($arResult['IS_HB']);
$href         = "/exel/matrix.php?type={$userTypeName}&app={$app}&hb={$hb}";
?>
<table border="0" cellspacing="0" cellpadding="10" id="legenda">
    <tr>
        <td class="confirmed"><strong>Подтвержденная встреча</strong></td>
        <td class="yellow"><strong style="color:#000;">Встреча назначенная<br/>участником</strong></td>
        <td class="red"><strong style="color:#FFF;">Встреча назначенная<br/>гостем</strong></td>
        <td><a class="custom-buttom" href="<?= $href; ?>">Генерировать Excel</a></td>
    </tr>
</table><br/>
<div class="navigate"><?= $arResult['PAGINATION'] ?></div>
<div class="timeslots-free">
    <? foreach ($arResult['TIMESLOTS_WITH_FREE_COMPANIES'] as $timesId => $timesList): ?>
        <div id="time-list<?= $timesId ?>">
            <? if (count($timesList['COMPANIES']) < 1): ?>
                Все таймслоты заняты
            <? else: ?>
                <select>
                    <? foreach ($arResult['TIMESLOTS_WITH_FREE_COMPANIES'][$timesId]['COMPANIES'] as $company): ?>
                        <option value="<?= $company['ID']; ?>"><?= $company['NAME']; ?></option>
                    <? endforeach; ?>
                </select>
            <? endif; ?>
        </div>
    <? endforeach; ?>
</div>
<?
switch ($arResult['USER_TYPE_NAME']) {
    case 'PARTICIPANT':
        include_once(dirname(__FILE__).'/participant.php');
        break;
    case 'GUEST':
        include_once(dirname(__FILE__).'/guest.php');
}
?>
<div class="navigate"><?= $arResult['PAGINATION'] ?></div>