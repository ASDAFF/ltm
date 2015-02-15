<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<? if(!empty($arResult["EXHIBITION"]["PARTICIPANT"])):?>
<? $exhibFormID = CFormMatrix::getPFormIDByExh($arResult["EXHIBITION"]["ID"]);?>
<? $arCollsAllID = array(16,17,18,19,20,21,22,23,24,26,27,28,29,30,31/*,32,33,35,36,37,38,39,106*/)?>
<? $arCollsUserIndex = array(0,1,2,3,4,5,6,7,8);?>
    <form method="post" action="" name="accept">
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
<? else:?>
<?= GetMessage("ADM_PARC_NO_PARTICIPANTS")?>
<? endif;?>

