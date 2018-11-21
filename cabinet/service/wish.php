<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Виш лист"); ?>
<? $APPLICATION->IncludeComponent('ds:meetings.wishlist.add', '', []); ?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>