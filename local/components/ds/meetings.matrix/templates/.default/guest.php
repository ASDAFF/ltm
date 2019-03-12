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
                <? foreach ($arResult['TIMESLOTS'] as $timeslot): ?>
                    <? if ($user['SCHEDULE'][$timeslot['ID']]['IS_BUSY'] && $user['SCHEDULE'][$timeslot['ID']]['STATUS'] == 'confirmed'): ?>
                        <? if ($user['SCHEDULE'][$timeslot['ID']]['USER_IS_SENDER']) {
                            $fromId = $user['ID'];
                            $toId   = $user['SCHEDULE'][$timeslot['ID']]['COMPANY_ID'];
                        } else {
                            $fromId = $user['SCHEDULE'][$timeslot['ID']]['COMPANY_ID'];
                            $toId   = $user['ID'];
                        } ?>
                        <td class="confirmed">
                        <?= $user['SCHEDULE'][$timeslot['ID']]['COMPANY_NAME']; ?><br/>
                        <?= $user['SCHEDULE'][$timeslot['ID']]['REP']; ?><br/>
                        <a href="<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId ?>&time=<?= $timeslot['ID'] ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                           target="_blank"
                           onclick="newWindConfirm('<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId
                           ?>&time=<?= $timeslot['ID'] ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400, 'Вы хотите отменить запрос?'); return false;">Отменить</a>
                    <? elseif ($user['SCHEDULE'][$timeslot['ID']]['IS_BUSY']): ?>
                        <? if ($user['SCHEDULE'][$timeslot['ID']]['USER_IS_SENDER']) {
                            $class  = "red";
                            $fromId = $user['ID'];
                            $toId   = $user['SCHEDULE'][$timeslot['ID']]['COMPANY_ID'];
                        } else {
                            $class  = "yellow";
                            $fromId = $user['SCHEDULE'][$timeslot['ID']]['COMPANY_ID'];
                            $toId   = $user['ID'];
                        }
                        ?>
                        <td class="<?= $class ?>">
                        <?= $user['SCHEDULE'][$timeslot['ID']]['COMPANY_NAME']; ?><br/>
                        <?= $user['SCHEDULE'][$timeslot['ID']]['REP']; ?><br/>
                        <a href="<?= $arResult['CONFIRM_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId ?>&time=<?= $timeslot['ID'] ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                           target="_blank"
                           onclick="newWind('<?= $arResult['CONFIRM_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId
                           ?>&time=<?= $timeslot['ID'] ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?=
                           $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Подтвердить</a>
                        <br/>
                        <a href="<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId ?>&time=<?= $timeslot['ID'] ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                           target="_blank"
                           onclick="newWindConfirm('<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $fromId ?>&to=<?= $toId
                           ?>&time=<?= $timeslot['ID'] ?>&app=<?= $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400, 'Вы хотите отменить запрос?'); return false;">Отменить</a>
                    <? else: ?>
                        <td class="times-list">
                        <a href="<?= $arResult['SEND_REQUEST_LINK'] ?>?id=<?= $user['ID'] ?>&time=<?= $timeslot['ID'] ?>&app=<?=
                        $arResult['APP_ID'] ?>&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                           target="_blank" data-timeslot="<?= $timeslot['ID'] ?>">Назначить</a>
                    <? endif; ?>
                    </td>
                <? endforeach; ?>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
</div>