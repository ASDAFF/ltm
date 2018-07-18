<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);
?>
<div><?= Loc::getMessage("WISHLIST_TITLE") ?></div>
<table class="section-request">
    <tr>
        <td class="appointments">
            <div class="wish-list"><?= Loc::getMessage("WISHLIST_YOU_LIKE_SEE") ?></div>
            <table class="morning-time">
                <tr>
                    <th>N</th>
                    <th><?= Loc::getMessage("WISHLIST_COMPANY") ?></th>
                </tr>
                <?
                foreach ($arResult['WISHLIST_FROM_USER'] as $key => $item) {
                    ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td> <?= $item['COMPANY_NAME'] ?></td>
                    </tr>
                    <?
                }
                ?>
            </table>
            <form action="">
                <div class="send-request">
                    <a href="/cabinet/service/wish.php?id=<?= $arResult["USER_ID"] ?>&to=0&app=<?= $arParams["EXHIBITION_ID"] ?>"
                       target="_blank"
                       onclick="newWish("<?= $arParams["USER_ID"] ?>","<?= $arParams["EXHIBITION_ID"] ?>
                    ","<?= $arParams['ADD_LINK_TO_WISHLIST'] ?>"); return false;">Отправить запрос</a>
                </div>
                <select name="wishlistComp" id="wishlistComp">
                    <option value="0"><?= Loc::getMessage("WISHLIST_CHOOSE_COMPANY") ?></option>
                    <? foreach ($arResult["COMPANIES"] as $item): ?>
                        <option value="<?= $item["company_id"] ?>"><?= $item["company_name"] ?></option>
                    <? endforeach; ?>
                </select>
            </form>
        </td>
        <td>
            <div class="wish-list"><?= Loc::getMessage("WISHLIST_TO_SEE_YOU") ?></div>
            <table class="morning-time">
                <tr>
                    <th>N</th>
                    <th><?= Loc::getMessage("WISHLIST_COMPANY") ?></th>
                </tr>
                <?
                foreach ($arResult['WISHLIST_FOR_USER'] as $key => $item) {
                    ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td> <?= $item['COMPANY_NAME'] ?></td>
                    </tr>
                    <?
                }
                ?>
            </table>
        </td>
    </tr>
</table>