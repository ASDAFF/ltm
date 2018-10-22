<? if ($arResult['APP_SETTINGS']['IS_LOCKED']) { ?>
    <p class="error">Appointments schedule blocked by the organizers</p>
<? } ?>
<table class="morning-time time-line">
    <tr>
        <th>Время</th>
        <th>Компания</th>
        <th>Представитель</th>
        <th>Статус</th>
        <th>Заметки</th>
        <th>Времени осталось</th>
    </tr>
    <? foreach ($arResult['SCHEDULE'] as $item): ?>
        <? if ($item['SLOT_TYPE'] === 'meet'): ?>
            <tr <? if ($item['STATUS'] == 'process' && !$item['SENT_BY_YOU']): ?>class="unconfirmed"<? endif; ?>>
                <td><?= $item['NAME'] ?></td>
                <? if (isset($item['COMPANY_NAME'])): ?>
                    <td><a href="/ajax/userInfo.php?id=<?= $item['COMPANY_ID'] ?>&res=<?= $item['FORM_RES'] ?>"
                           class="user-info-wind fancybox.ajax"
                           target="_blank"><?= $item['COMPANY_REP'] ?></a></td>
                    <td><?= $item['COMPANY_NAME'] ?></td>
                <? else: ?>
                    <td colspan="2">
                        <select name="company_id" class="chose-company" id="companys<?= $item['TIMESLOT_ID'] ?>">
                            <option value="0">Выберите компанию</option>
                            <? foreach ($item['LIST'] as $company): ?>
                                <option value="<?= $company['ID'] ?>"><?= $company['COMPANY'] ?></option>
                            <? endforeach; ?>
                        </select>
                    </td>
                <? endif; ?>
                <td width="110">
                    <?
                    switch ($item['STATUS']) {
                        case 'confirmed':
                            echo 'Подтвержден';
                            break;
                        case 'process':
                            if ($item['SENT_BY_YOU']):?>
                                <a href="<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['COMPANY_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['COMPANY_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Отменить</a>
                            <? else: ?>
                                <a href="<?= $arResult['CONFIRM_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['COMPANY_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $arResult['CONFIRM_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['COMPANY_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Подтвердить</a>
                                <br/>
                                <a href="<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $item['COMPANY_ID'] ?>&to=<?= $arResult['USER_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $item['COMPANY_ID'] ?>&to=<?= $arResult['USER_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Отменить</a>
                            <?
                            endif;
                            break;
                        case 'free':
                            if ($arResult['APP_SETTINGS']['IS_LOCKED']) { ?>
                                Заблокировано
                            <? } else { ?>
                                <a href="<?= $arResult['SEND_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=0&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newRequest('<?= $arResult['USER_ID'] ?>','<?= $item['TIMESLOT_ID'] ?>','<?= $arResult['APP_ID'] ?>','<?= $arResult['SEND_REQUEST_LINK'] ?>', 'p', '<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>'); return false;">Послать
                                    запрос</a>
                                <?
                            }
                            break;
                    }
                    ?>
                </td>
                <?
                ?>
                <td width="105"><?= $item['NOTES']; ?></td>
                <td width="50">
                    <? if ($item['STATUS'] != 'confirmed'): ?>
                        <?= $item['TIME_LEFT']; ?><? if ($item['TIME_LEFT']): ?>ч<? endif; ?>
                    <? endif; ?>
                </td>
            </tr>
        <? endif; ?>
    <? endforeach; ?>
</table>
<div class="pull-overflow generate-file">
    <div class="pull-left">
        <a onclick="newWind('<?= $arResult['WISHLIST_LINK'] ?>_guest.php?id=<?= $arResult['USER_ID'] ?>&exhib=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $arResult['APP_ID'] ?>&type=g&mode=pdf', 600, 700); return false;"
           target="_blank"
           href="<?= $arResult['WISHLIST_LINK'] ?>_guest.php?id=<?= $arResult['USER_ID'] ?>&exhib=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $arResult['APP_ID'] ?>&type=g&mode=pdf">Генерировать
            вишлист PDF</a>
    </div>
    <div class="pull-right">
        <a onclick="newWind('<?= $arResult['SCHEDULE_LINK'] ?>_guest.php?id=<?= $arResult['USER_ID'] ?>&exhib=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $arResult['APP_ID'] ?>&type=g&mode=pdf', 600, 700); return false;"
           target="_blank"
           href="<?= $arResult['SCHEDULE_LINK'] ?>_guest.php?id=<?= $arResult['USER_ID'] ?>&exhib=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $arResult['APP_ID'] ?>&type=g&mode=pdf">Генерировать
            расписание PDF</a>
    </div>
</div>
