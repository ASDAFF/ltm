<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="private-office" >
<?if($arResult["FORM_TYPE"] == "login"):?>
    <div id = "form" >
    <form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

    <?if($arResult["BACKURL"] <> ''):?>
    	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?endif?>
    <?foreach ($arResult["POST"] as $key => $value):?>
    	<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
    <?endforeach?>
        <input type="hidden" name="AUTH_FORM" value="Y" />
    	<input type="hidden" name="TYPE" value="AUTH" />
    	<input type="hidden" name="USER_REMEMBER" value="Y" />

    	<span class = "form_title"><?= GetMessage("AUTH_AUTH")?><br><?= GetMessage("AUTH_FOR_EXHIBITIRS")?></span>
    	<input type = "text" name="USER_LOGIN" class = "mail" value="<?=$arResult["USER_LOGIN"]?>" placeholder="<?= GetMessage("AUTH_LOGIN")?>"/>
    	<input type = "password" name="USER_PASSWORD" class = "pass" value = "" placeholder="<?= GetMessage("AUTH_PASSWORD")?>"/>
    	<?if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR']):?>
	       <div class="error-mes"><?=ShowMessage($arResult['ERROR_MESSAGE']); ?></div>
    	<? endif?>
    	<input type = "submit" name="Login" class = "but" value = "<?= GetMessage("AUTH_LOGIN_BUTTON")?>" />
    	<a href = "<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" class = "forgot"><?= GetMessage("AUTH_FORGOT_PASSWORD")?></a>
    </form>
    </div>
<? //if($arResult["FORM_TYPE"] == "login")
else:
?>
    <? switch ($userType = $_SESSION["USER_TYPE"])
    {
        case "PARTICIPANT" : require_once ("participant.php"); break;
        case "GUEST" : require_once ("guest.php"); break;
        default:?>
            <div id="form" class="form">
                <p><?=GetMessage('AUTH_LOGIN_ERROR')?></p>
                <div class="leave clearfix">
                    <a href="/?logout=yes" title="EXIT" class="exit"><?=GetMessage("AUTH_P_EXIT")?></a>
                </div>
            </div><?
        break;
    }
    ?>
<? endif;?>
</div>