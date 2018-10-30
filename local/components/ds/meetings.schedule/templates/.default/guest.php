<? if ($userInfo['APP_SETTINGS']['IS_LOCKED']) { ?>
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
    <? foreach ($userInfo['SCHEDULE'] as $item): ?>
        <? if ($item['slot_type'] === 'meet'): ?>
            <tr <? if ($item['status'] == 'process' && !$item['sent_by_you']): ?>class="unconfirmed"<? endif; ?>>
                <td><?= $item['name'] ?></td>
                <? if (isset($item['company_name'])): ?>
                    <td>
                        <a
                                href="/ajax/userInfo.php?id=<?= $item['company_id'] ?>&res=<?= $item['form_res'] ?>"
                                class="user-info-wind fancybox.ajax"
                                target="_blank"
                        >
                            <?= $item['company_rep'] ?>
                        </a>
                    </td>
                    <td><?= $item['company_name'] ?></td>
                <? else: ?>
                    <td colspan="2">
                        <select name="company_id" class="chose-company" id="companys<?= $item['timeslot_id'] ?>">
                            <option value="0">Выберите компанию</option>
                            <? foreach ($item['list'] as $company): ?>
                                <option value="<?= $company['ID'] ?>"><?= $company['COMPANY'] ?></option>
                            <? endforeach; ?>
                        </select>
                    </td>
                <? endif; ?>
                <td width="110">
                    <?
                    switch ($item['status']) {
                        case 'confirmed':
                            echo 'Подтвержден';
                            break;
                        case 'process':
                            if ($item['sent_by_you']):?>
                                <a href="<?= $userInfo['REJECT_REQUEST_LINK'] ?>?id=<?= $userInfo['USER_ID'] ?>&to=<?= $item['company_id'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=p&exib_code=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $userInfo['REJECT_REQUEST_LINK'] ?>?id=<?= $userInfo['USER_ID'] ?>&to=<?= $item['company_id'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=p&exib_code=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Отменить</a>
                            <? else: ?>
                                <a href="<?= $userInfo['CONFIRM_REQUEST_LINK'] ?>?id=<?= $userInfo['USER_ID'] ?>&to=<?= $item['company_id'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=p&exib_code=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $userInfo['CONFIRM_REQUEST_LINK'] ?>?id=<?= $userInfo['USER_ID'] ?>&to=<?= $item['company_id'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=p&exib_code=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Подтвердить</a>
                                <br/>
                                <a href="<?= $userInfo['REJECT_REQUEST_LINK'] ?>?id=<?= $item['company_id'] ?>&to=<?= $userInfo['USER_ID'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=p&exib_code=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $userInfo['REJECT_REQUEST_LINK'] ?>?id=<?= $item['company_id'] ?>&to=<?= $userInfo['USER_ID'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=p&exib_code=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Отменить</a>
                            <?
                            endif;
                            break;
                        case 'free':
                            if ($userInfo['APP_SETTINGS']['IS_LOCKED']) { ?>
                                Заблокировано
                            <? } else { ?>
                                <a href="<?= $userInfo['SEND_REQUEST_LINK'] ?>?id=<?= $userInfo['USER_ID'] ?>&to=0&time=<?= $item['timeslot_id'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=p&exib_code=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newRequest('<?= $userInfo['USER_ID'] ?>','<?= $item['timeslot_id'] ?>','<?= $userInfo['APP_ID'] ?>','<?= $userInfo['SEND_REQUEST_LINK'] ?>', 'p', '<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>'); return false;">Послать
                                    запрос</a>
                                <?
                            }
                            break;
                    }
                    ?>
                </td>
                <?
                ?>
                <td width="105"><?= $item['notes']; ?></td>
                <td width="50">
                    <? if ($item['status'] != 'confirmed'): ?>
                        <?= $item['time_left']; ?><? if ($item['time_left']): ?>ч<? endif; ?>
                    <? endif; ?>
                </td>
            </tr>
        <? endif; ?>
    <? endforeach; ?>
</table>
<div class="pull-overflow generate-file">
    <div class="pull-left">
        <a onclick="newWind('<?= $userInfo['WISHLIST_LINK'] ?>_guest.php?id=<?= $userInfo['USER_ID'] ?>&exhib=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=g&mode=pdf', 600, 700); return false;"
           target="_blank"
           href="<?= $userInfo['WISHLIST_LINK'] ?>_guest.php?id=<?= $userInfo['USER_ID'] ?>&exhib=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=g&mode=pdf">Генерировать
            вишлист PDF</a>
    </div>
    <div class="pull-right">
        <a onclick="newWind('<?= $userInfo['SCHEDULE_LINK'] ?>_guest.php?id=<?= $userInfo['USER_ID'] ?>&exhib=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=g&mode=pdf', 600, 700); return false;"
           target="_blank"
           href="<?= $userInfo['SCHEDULE_LINK'] ?>_guest.php?id=<?= $userInfo['USER_ID'] ?>&exhib=<?= $userInfo['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $userInfo['APP_ID'] ?>&type=g&mode=pdf">Генерировать
            расписание PDF</a>
    </div>
</div>
