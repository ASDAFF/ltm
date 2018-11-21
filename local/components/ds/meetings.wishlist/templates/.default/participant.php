<? if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div>In this section you can request guests whose schedules have already been filled.</div>
<table class="section-request">
    <tr>
        <td class="appointments">
            <div class="wish-list">My wish list for appointments outside of the morning session</div>
            <table class="morning-time">
                <tr>
                    <th>№</th>
                    <th>Company</th>
                </tr>
                <? $counter = 0;
                foreach ($arResult['WISH_IN'] as $item):?>
                    <? $counter++; ?>
                    <tr>
                        <td><?= $counter ?></td>
                        <td> <?= $item['company_name'] ?></td>
                    </tr>
                <? endforeach; ?>
            </table>
            <form action="">
                <a href="<?= $arParams['ADD_LINK_TO_WISHLIST']; ?>?id=<?= $arResult['USER_ID'] ?>&to=0&app=<?= $arResult['APP_ID'] ?>"
                   target="_blank"
                   onclick="newWish('<?= $arResult['USER_ID'] ?>','<?= $arResult['APP_ID'] ?>','<?= $arParams['ADD_LINK_TO_WISHLIST']; ?>'); return false;">Send
                    a request</a>
                </div>
                <select name="wishlistComp" id="wishlistComp">
                    <option value="0">Choose a company</option>
                    <? foreach ($arResult['COMPANIES'] as $item): ?>
                        <option value="<?= $item['company_id'] ?>"><?= $item['company_name'] ?></option>
                    <? endforeach; ?>
                </select>
            </form>
        </td>
        <td>
            <div class="wish-list">The following guests are interested in an appointment with you outside of the morning session
            </div>
            <table class="morning-time">
                <tr>
                    <th>№</th>
                    <th>Company</th>
                </tr>
                <? $counter = 0;
                foreach ($arResult['WISH_OUT'] as $item):?>
                    <? $counter++; ?>
                    <tr>
                        <td><?= $counter ?></td>
                        <td> <?= $item['company_name'] ?></td>
                    </tr>
                <? endforeach; ?>
            </table>
        </td>
    </tr>
</table>