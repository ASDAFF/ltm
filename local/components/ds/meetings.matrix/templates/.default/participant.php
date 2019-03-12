<?
if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<div class="table-results">
    <table class="table table-fixed">
        <thead>
        <tr>
            <th>Компания и представитель</th>
            <? foreach ($arResult['TIMESLOTS'] as $timeslot): ?>
                <th><?= $timeslot['NAME']; ?></th>
            <? endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <? foreach ($arResult['MATRIX'] as $user): ?>
            <tr>
                <td>
                    <?= $user['NAME']; ?><br/>
                    <?= $user['REP']; ?>
                </td>
                <? foreach ($arResult['TIMESLOTS'] as $timeslot):
                    $timeSlotInfo = $user['SCHEDULE'][$timeslot['ID']];
                    $scheduleStatus = $timeSlotInfo['STATUS'];
                    ?>
                    <? if ($timeSlotInfo['IS_BUSY'] && $scheduleStatus == 'confirmed'): ?>
                    <? if ($timeSlotInfo['USER_IS_SENDER']) {
                        $fromId = $user['ID'];
                        $toId   = $timeSlotInfo['COMPANY_ID'];
                    } else {
                        $fromId = $timeSlotInfo['COMPANY_ID'];
                        $toId   = $user['ID'];
                    } ?>
                    <td class="confirmed">
                    <?= $timeSlotInfo['COMPANY_NAME']; ?><br/>
                    <?= $timeSlotInfo['REP']; ?><br/>
                    <a href="<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId ?>&time=<?= $timeslot['ID']
                    ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&type=p"
                       target="_blank"
                       onclick="newWindConfirm('<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId
                       ?>&time=<?= $timeslot['ID'] ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?=
                       $arResult['PARAM_EXHIBITION']['CODE'] ?>&type=p', 500, 400, 'Вы хотите отменить запрос?'); return false;">Отменить</a>
                <? elseif ($scheduleStatus == 'reserve'):
                    $fromId = $user['ID'];
                    $reserveLink = $arResult['RESERVE_REQUEST_LINK']."?id=".$fromId."&time=".$timeslot['ID']."&app=".$arResult['APP_ID']."&type=p&exib_code=".$arResult['PARAM_EXHIBITION']['CODE']; ?>
                    <td class="reserved">
                        Забронирован<br/>
                        <a href="<?= $reserveLink ?>" onclick="newWind('<?= $reserveLink ?>', 500, 200); return false;"
                           target="_blank">Освободить</a>
                    </td>
                <? elseif ($timeSlotInfo['IS_BUSY']): ?>
                    <?
                    if ($timeSlotInfo['USER_IS_SENDER']) {
                        $class  = "yellow";
                        $fromId = $user['ID'];
                        $toId   = $timeSlotInfo['COMPANY_ID'];
                    } else {
                        $class  = "red";
                        $fromId = $timeSlotInfo['COMPANY_ID'];
                        $toId   = $user['ID'];
                    }
                    ?>
                    <td class="<?= $class ?>">
                    <?= $timeSlotInfo['COMPANY_NAME'] ?><br/>
                    <?= $timeSlotInfo['REP'] ?><br/>
                    <a href="<?= $arResult['CONFIRM_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId ?>&time=<?= $timeslot['ID']
                    ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&type=p"
                       target="_blank"
                       onclick="newWind('<?= $arResult['CONFIRM_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId ?>&time=<?=
                       $timeslot['ID'] ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE']
                       ?>&type=p', 500, 400); return false;">Подтвердить</a>
                    <br/>
                    <a href="<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId ?>&time=<?= $timeslot['ID']
                    ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&type=p"
                       target="_blank"
                       onclick="newWindConfirm('<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId
                       ?>&time=<?= $timeslot['ID'] ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&type=p', 500, 400, 'Вы хотите отменить запрос?'); return false;">Отменить</a>
                <? else: ?>
                    <td class="times-list">
                    <a href="<?= $arResult['SEND_REQUEST_LINK'] ?>?id=<?= $user['ID'] ?>&time=<?= $timeslot['ID'] ?>&app=<?=
                    $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&type=p"
                       target="_blank" data-timeslot="<?= $timeslot['ID'] ?>">Назначить</a>
                <? endif; ?>
                    </td>
                <? endforeach; ?>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
</div>