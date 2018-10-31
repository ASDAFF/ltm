<?
/**
 * @var CBitrixComponent $arResult
 */
?>
<? if ($arResult['APP_SETTINGS']['IS_LOCKED']) { ?>
    <p class="error">Appointments schedule blocked by the organizers</p>
<? } ?>
<table class="morning-time time-line">
    <tr>
        <th>Time</th>
        <th>Company</th>
        <th>Representative</th>
        <th>Status</th>
        <th>Notes</th>
        <th>Time left /<br>Hold slot</th>
    </tr>
    <? foreach ($arResult['SCHEDULE'] as $item): ?>
        <? if ($item['slot_type'] === 'meet'): ?>
            <tr class="<? if ($item['status'] == 'process' && !$item['sent_by_you']): ?>unconfirmed<? endif; ?> <?= $item['status'] ?>">
                <td><?= $item['name'] ?></td>
                <? if ($item['status'] == 'reserve'): ?>
                    <td colspan="2">
                        Reserved by you
                    </td>
                <? elseif (isset($item['company_name'])): ?>
                    <td>
                        <a
                                href="/ajax/userInfo.php?id=<?= $item['company_id'] ?>&userType=GUEST"
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
                            <option value="0">Choose a company</option>
                            <? foreach ($item['list'] as $company): ?>
                                <option value="<?= $company['ID'] ?>"><?= $company['COMPANY'] ?></option>
                            <? endforeach; ?>
                        </select>
                    </td>
                <? endif; ?>
                <td width="80">
                    <?
                    switch ($item['status']) {
                        case 'confirmed':
                            echo 'confirmed';
                            break;
                        case 'process':
                            if ($item['sent_by_you']):?>
                                <a href="<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['company_id'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['company_id'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $arResult['APP_ID']
                                   ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Cancel</a>
                            <? else: ?>
                                <a href="<?= $arResult['CONFIRM_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['company_id'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $arResult['CONFIRM_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['company_id'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Accept</a>
                                <br/>
                                <a href="<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $item['company_id'] ?>&to=<?= $arResult['USER_ID'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $item['company_id'] ?>&to=<?= $arResult['USER_ID'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Decline</a>
                            <?
                            endif;
                            break;
                        case 'free':
                            if ($arResult['APP_SETTINGS']['IS_LOCKED']) { ?>
                                Blocked
                            <? } else { ?>
                                <a href="<?= $arResult['SEND_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=0&time=<?= $item['timeslot_id'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newRequest('<?= $arResult['USER_ID'] ?>','<?= $item['timeslot_id'] ?>','<?= $arResult['APP_ID'] ?>','<?= $arResult['SEND_REQUEST_LINK'] ?>', 'p', '<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>'); return false;">Send
                                    a request</a>
                                <?
                            }
                            break;
                    } ?>
                </td>
                <td width="100"><?= $item['notes']; ?></td>
                <td width="60">
                    <? if ($item['status'] == 'free'):
                        $reserveLink = $arResult['RESERVE_REQUEST_LINK']."?id=".$arResult['USER_ID']."&time=".$item['timeslot_id']."&app=".$arResult['APP_ID']."&type=p&exib_code=".$arResult['PARAM_EXHIBITION']['CODE'];
                        ?>
                        <a href="<?= $reserveLink ?>" class="time-reserve-wind fancybox.ajax" target="_blank">Reserve</a>
                    <? elseif ($item['status'] == 'reserve'):
                        $reserveLink = $arResult['RESERVE_REQUEST_LINK']."?id=".$arResult['USER_ID']."&time=".$item['timeslot_id']."&app=".$arResult['APP_ID']."&type=p&exib_code=".$arResult['PARAM_EXHIBITION']['CODE'];
                        ?>
                        <a href="<?= $reserveLink ?>" class="time-reserve-wind fancybox.ajax" target="_blank">Release</a>
                    <? elseif ($item['status'] != 'confirmed'): ?>
                        <?= $item['time_left']; ?><? if ($item['time_left']): ?>h<? endif; ?>
                    <? endif; ?>
                </td>
            </tr>
        <? endif; ?>
    <? endforeach; ?>

</table>
<div class="pull-overflow generate-file">
    <div class="pull-left">
        <a onclick="newWind('<?= $arResult['WISHLIST_LINK'] ?>_particip.php?id=<?= $arResult['USER_ID'] ?>&exhib=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&mode=pdf', 600, 700); return false;"
           target="_blank"
           href="<?= $arResult['WISHLIST_LINK'] ?>_particip.php?id=<?= $arResult['USER_ID'] ?>&exhib=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&mode=pdf">Generate
            wish-list PDF</a>
    </div>
    <div class="pull-right">
        <a onclick="newWind('<?= $arResult['SCHEDULE_LINK'] ?>_particip.php?id=<?= $arResult['USER_ID'] ?>&exhib=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&mode=pdf', 600, 700); return false;"
           target="_blank"
           href="<?= $arResult['SCHEDULE_LINK'] ?>_particip.php?id=<?= $arResult['USER_ID'] ?>&exhib=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&mode=pdf">Generate
            schedule PDF</a>
    </div>
</div>
