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
        <? if ($item['SLOT_TYPE'] === 'meet'): ?>
            <tr class="<? if ($item['STATUS'] == 'process' && !$item['SENT_BY_YOU']): ?>unconfirmed<? endif; ?> <?= $item['STATUS'] ?>">
                <td><?= $item['COMPANY_NAME'] ?></td>
                <? if ($item['STATUS'] == 'reserve'): ?>
                    <td colspan="2">
                        Reserved by you
                    </td>
                <? elseif (isset($item['COMPANY_NAME'])): ?>
                    <td>
                        <a href="/ajax/userInfo.php?id=<?= $item['COMPANY_ID'] ?>" class="user-info-wind fancybox.ajax"
                           target="_blank">
                            <?= $item['COMPANY_REP'] ?>
                        </a>
                    </td>
                    <td><?= $item['COMPANY_NAME'] ?></td>
                <? else: ?>
                    <td colspan="2">
                        <select name="company_id" class="chose-company" id="companys<?= $item['TIMESLOT_ID'] ?>">
                            <option value="0">Choose a company</option>
                            <? foreach ($item['LIST'] as $company): ?>
                                <option value="<?= $company['ID'] ?>"><?= $company['COMPANY'] ?></option>
                            <? endforeach; ?>
                        </select>
                    </td>
                <? endif; ?>
                <td width="80">
                    <?
                    switch ($item['STATUS']) {
                        case 'confirmed':
                            echo 'confirmed';
                            break;
                        case 'process':
                            if ($item['SENT_BY_YOU']):?>
                                <a href="<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['COMPANY_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['COMPANY_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID']
                                   ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Cancel</a>
                            <? else: ?>
                                <a href="<?= $arResult['CONFIRM_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['COMPANY_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $arResult['CONFIRM_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=<?= $item['COMPANY_ID'] ?>&time=<?= $item['timeslot_id'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Accept</a>
                                <br/>
                                <a href="<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $item['COMPANY_ID'] ?>&to=<?= $arResult['USER_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newWind('<?= $arResult['REJECT_REQUEST_LINK'] ?>?id=<?= $item['COMPANY_ID'] ?>&to=<?= $arResult['USER_ID'] ?>&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>', 500, 400); return false;">Decline</a>
                            <?
                            endif;
                            break;
                        case 'free':
                            if ( $arResult['APP_SETTINGS']['IS_LOCKED']) { ?>
                                Blocked
                            <? } else { ?>
                                <a href="<?= $arResult['SEND_REQUEST_LINK'] ?>?id=<?= $arResult['USER_ID'] ?>&to=0&time=<?= $item['TIMESLOT_ID'] ?>&app=<?= $arResult['APP_ID'] ?>&type=p&exib_code=<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>"
                                   target="_blank"
                                   onclick="newRequest('<?= $arResult['USER_ID'] ?>','<?= $item['TIMESLOT_ID'] ?>','<?= $arResult['APP_ID'] ?>','<?= $arResult['SEND_REQUEST_LINK'] ?>', 'p', '<?= $arResult['PARAM_EXHIBITION']['CODE'] ?>'); return false;">Send
                                    a request</a>
                                <?
                            }
                            break;
                    } ?>
                </td>
                <td width="100"><?= $item['NOTES']; ?></td>
                <td width="60">
                    <? if ($item['STATUS'] == 'free'):
                        $reserveLink = $arResult['RESERVE_REQUEST_LINK']."?id=".$arResult['USER_ID']."&time=".$item['TIMESLOT_ID']."&app=".$arResult['APP_ID']."&type=p&exib_code=".$arResult['PARAM_EXHIBITION']['CODE'];
                        ?>
                        <a href="<?= $reserveLink ?>" class="time-reserve-wind fancybox.ajax" target="_blank">Reserve</a>
                    <? elseif ($item['STATUS'] == 'reserve'):
                        $reserveLink = $arResult['RESERVE_REQUEST_LINK']."?id=".$arResult['USER_ID']."&time=".$item['TIMESLOT_ID']."&app=".$arResult['APP_ID']."&type=p&exib_code=".$arResult['PARAM_EXHIBITION']['CODE'];
                        ?>
                        <a href="<?= $reserveLink ?>" class="time-reserve-wind fancybox.ajax" target="_blank">Release</a>
                    <? elseif ($item['STATUS'] != 'confirmed'): ?>
                        <?= $item['TIME_LEFT']; ?><? if ($item['TIME_LEFT']): ?>h<? endif; ?>
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
