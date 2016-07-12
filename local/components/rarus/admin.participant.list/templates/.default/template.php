<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<? $exhibFormID = CFormMatrix::getPFormIDByExh($arResult["EXHIBITION"]["ID"]);?>
<? $arCollsAllID = array(16,17,18,19,20,21,22,23,24,26,27,28,29,30,31/*,32,33,35,36,37,38,39,106*/)?>
<? $arCollsUserIndex = array(0,1,2,3,4,5,6,7,8);?>
<div id="modal_form"><!-- Само окно -->
    <span id="modal_close">X</span> <!-- Кнопка закрыть -->
    <form action="/ajax/all_pdf_shedule.php">
        <input type="hidden" name="type" value="particip" id="pdf_type"/>
        <input type="hidden" name="hb" value="" id="pdf_hb"/>
        <input type="hidden" name="to" value="" id="pdf_to"/>
        <input type="hidden" name="app" value="<?=$arParams["EXHIB_CODE"]?>" id="pdf_app"/>
        <p class="error" id="pdf_error"></p>
        <p>Введи Email на который нужно отправить ссылку для скачивания</p>
        <input type="text" name="email" value="" id="pdf_email"/>
        <button type="submit" name="send" value="send" id="generate_pdf">Отправить</button>
    </form>
</div>
<div id="overlay"></div><!-- Подложка -->
<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="accept">
    <?
    if(("Y" == $arParams["SPAM"]))
    {
        include("spam.php");
    }
    elseif(("Y" == $arParams["CONFIRMED"]))
    {
      include("confirmed.php");
    }
    elseif ("N" == $arParams["CONFIRMED"] || "N" == $arParams["ACTIVE"])
    {
        include("unconfirmed.php");
    }?>
</form>
