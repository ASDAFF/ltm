<?php // Блок с данными пользователя ?>

<?
$curDir = $APPLICATION->GetCurDir();
?>
	<div id="form" class="form rus">
		<?php // Выводим фото + имя?>
		<div class="information-data pull-overflow">
			<div class="member">
			<? if(isset($arResult["PROFILE"]["PHOTO"]) && strlen($arResult["PROFILE"]["PHOTO"]["src"]) > 0)
			{
				$imgSrc = $arResult["PROFILE"]["PHOTO"]["src"];
			}?>
				<img src="<?= $imgSrc?>" alt="">
			</div>
			<div class="participant">
				<div class="name"><?= $arResult["PROFILE"]["NAME"]?></div>
				<div class="name"><?= $arResult["PROFILE"]["LAST_NAME"]?></div>
				<div class="type-helvetica"><?= ($arResult["PROFILE"]["TYPE"] == "PARTICIPANT")?GetMessage("AUTH_P_TYPE_EXHIBITOR"):GetMessage("AUTH_P_TYPE_BUYER")?></div>
			</div>
		</div>

		<? if($USER->IsAdmin() || (GUEST_CABINET == "Y" && $arResult["CONFIRMED"] == "Y")):?>
		<?php // Редактировать данные ?>
		<div class="edit-profile">
			<div class="redact"><a href="<?= $arResult["PROFILE"]["EDIT_LINK"]?>" title="Edit profile" <?= ($curDir == stristr($arResult["PROFILE"]["EDIT_LINK"], "?", true))?"class='bolder'":"";?>><?= GetMessage("AUTH_G_EDIT_PROFILE")?></a></div>
			<div class="colleague-reg"><a href="<?= $arResult["PROFILE"]["EDIT_COLLEAGUE_LINK"]?>" title="" <?= ($curDir == stristr($arResult["PROFILE"]["EDIT_COLLEAGUE_LINK"], "?", true))?"class='bolder'":"";?>><?= GetMessage("AUTH_G_EDIT_COLLEAGUE")?></a></div>
			<div class="mail-list pull-overflow"><a href="<?= $arResult["PROFILE"]["MESSAGES"]["LINK"]?>" title="<?= GetMessage("AUTH_G_MESSAGES")?>" <?= ($curDir == stristr($arResult["PROFILE"]["MESSAGES"]["LINK"], "?", true))?"class='bolder'":"";?>><span class="mess"><?= GetMessage("AUTH_G_MESSAGES")?></span> <? if($arResult["PROFILE"]["MESSAGES"]["COUNT"] > 0):?><span class="count pull-right"><?= $arResult["PROFILE"]["MESSAGES"]["COUNT"]?></span><? endif;?></a></div>
			<div><a href="<?= $arResult["PROFILE"]["SCHEDULE"]["LINK"]?>" title="<?= GetMessage("AUTH_G_SHEDULE")?>" <?= ($curDir == stristr($arResult["PROFILE"]["SCHEDULE"]["LINK"], "?", true))?"class='bolder'":"";?>><?= GetMessage("AUTH_G_SHEDULE")?></a></div>
		</div>
		<? else:?>
			<? if(GUEST_CABINET == "Y"):?>
			    <p><?= GetMessage("AUTH_G_CAB_UNCONFIRMED")?></p>
			<? else:?>
				<p><?= GetMessage("AUTH_G_CAB_CLOSE")?></p>
			<? endif;?>
		<? endif;?>

		<?php // Выход?>
		<div class="leave pull-overflow">
		    <? if($USER->IsAdmin() || (GUEST_CABINET == "Y" && $arResult["CONFIRMED"] == "Y")):?>
		        <a href="<?= $arResult["PROFILE"]["PERSONAL_LINK"]?>" title="Личный кабинет" class="pc">Личный кабинет</a>
			<? endif;?>
			<a href="/?logout=yes" title="EXIT" class="exit type-helvetical"><?=GetMessage("AUTH_P_EXIT")?></a>
		</div>
	</div>
<?php // Блок с данными пользователя ?>