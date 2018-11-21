<? if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div>Здесь вы можете запросить только тех участников, чьи расписания заполнены</div>
<table class="section-request">
    <tr>
        <td class="appointments">
            <div class="wish-list">Вы хотели бы видеть следующие компании</div>
            <table class="morning-time">
                <tr>
                    <th>N</th>
                    <th>Компания</th>
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
                <div class="send-request">
                    <a href="<?= $arParams['ADD_LINK_TO_WISHLIST'] ?>?id=<?= $arResult['USER_ID'] ?>&to=0&app=<?= $arResult['APP_ID'] ?>"
                       target="_blank"
                       onclick="newWish('<?= $arResult['USER_ID'] ?>','<?= $arResult['APP_ID'] ?>','<?= $arParams['ADD_LINK_TO_WISHLIST']; ?>'); return false;">Отправить
                        запрос</a>
                </div>
                <select name="wishlistComp" id="wishlistComp">
                    <option value="0">Выберите компанию</option>
                    <? foreach ($arResult['COMPANIES'] as $item): ?>
                        <option value="<?= $item['company_id'] ?>"><?= $item['company_name'] ?></option>
                    <? endforeach; ?>
                </select>
            </form>
        </td>
        <td>
            <div class="wish-list">С Вами также хотели бы встретиться следующие участники</div>
            <table class="morning-time">
                <tr>
                    <th>N</th>
                    <th>Компания</th>
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