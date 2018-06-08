<?php
/**
 * Created by PhpStorm.
 * User: Anatoliy Kim
 * Date: 23.10.2017
 * Time: 9:21
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use \Bitrix\Main\Localization\Loc;
$request = \Bitrix\Main\HttpContext::getCurrent()->getRequest();
?>

<div class="storage-popup">
    <form method="post" action="<?=$arResult["ACTION_URL"]?>" name="todeletemass">
        <input type="hidden" name="VALUES" value="<?=$arResult["VALUES"]?>">
        <input type="hidden" name="TYPE" value="todeletemass">
        <h2>Массовое удаление гостей.</h2>
        <div class="clear"></div>
        <input type="submit" value="Удалить">
    </form>
</div>
<script>
    $(function () {
        $('form[name=todeletemass]').attr('action', window.location.href)
    }());
</script>