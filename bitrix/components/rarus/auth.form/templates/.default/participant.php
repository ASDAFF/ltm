<?php // ���� � ������� ������������ ?>
<?
$curDir = $APPLICATION->GetCurDir();
?>
	<div id="form" class="form">
		<?php // ������� ���� + ���?>
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

		<?//pre($arResult["EXHIBITION"], "amanda1876");?>
		<? if($USER->IsAdmin() || (PARTICIPANT_CABINET == "Y" && !empty($arResult["EXHIBITION"]["CONFIRMED"]))):?>
		<?php // ������������� ������ ?>
		<div class="edit-profile">
			<div><a href="<?= $arResult["PROFILE"]["EDIT_LINK"]?>" title="<?= GetMessage("AUTH_P_EDIT_PROFILE")?>" <?= ($curDir == stristr($arResult["PROFILE"]["EDIT_LINK"], "?", true))?"class='bolder'":"";?>><?= GetMessage("AUTH_P_EDIT_PROFILE")?></a></div>
			<div><a href="<?= $arResult["PROFILE"]["MESSAGE_LINK"]?>" title="<?= GetMessage("AUTH_P_CONTACT")?>" <?= ($curDir == stristr($arResult["PROFILE"]["MESSAGES"]["LINK"], "?", true))?"class='bolder'":"";?>><?= GetMessage("AUTH_P_CONTACT")?></a></div>
			<div><a href="<?= $arResult["PROFILE"]["EDIT_COMPANY_LINK"]?>" title="<?= GetMessage("AUTH_P_EXHIBITOR_PAGE")?>" <?= ($curDir == stristr($arResult["PROFILE"]["EDIT_COMPANY_LINK"], "?", true))?"class='bolder'":"";?>><?= GetMessage("AUTH_P_EXHIBITOR_PAGE")?></a></div>
			<div><a href="/members/<?= ($_REQUEST["UID"])?"?UID=" . $_REQUEST["UID"]:"";?>" title="<?= GetMessage("AUTH_P_CATALOGUE")?>" <?= ($curDir == "/members/")?"class='bolder'":"";?>><?= GetMessage("AUTH_P_CATALOGUE")?></a></div>
			<div><a href="<?= $arResult["PROFILE"]["PERSONAL_LINK"]?>" title="Personal Cabinet" >Personal Cabinet</a></div>
		</div>
		<? else:?>
			<? if(PARTICIPANT_CABINET == "Y"):?>
			    <p><?= GetMessage("AUTH_P_CAB_UNCONFIRMED")?></p>
			<? else:?>
				<p><?= GetMessage("AUTH_P_CAB_CLOSE")?></p>
			<? endif;?>
		<? endif;?>

		<?php // �����?>
		<div class="leave pull-overflow">
		    <? if($USER->IsAdmin() || (PARTICIPANT_CABINET == "Y")):?>
		        <a href="<?= $arResult["PROFILE"]["COMPANY_LINK"]?>" title="<?= $arResult["PROFILE"]["COMPANY_NAME"]?>" target="_blank"><?= $arResult["PROFILE"]["COMPANY_NAME"]?></a>
			<? endif;?>
			<a href="/?logout=yes" title="EXIT" class="exit type-helvetical"><?=GetMessage("AUTH_P_EXIT")?></a>
		</div>
	</div>
<?php // ���� � ������� ������������ ?>

<? if($USER->IsAdmin() || (PARTICIPANT_CABINET == "Y" && !empty($arResult["EXHIBITION"]["CONFIRMED"]))):?>
    <?php // ����������������� ?>
    <? foreach ($arResult["EXHIBITION"]["CONFIRMED"] as $arExhibition):?>
    	<div class="gray-grid register">
    		<div class="head">
    			<div class="type-helvetica"><?= $arExhibition["EXH_NAME"]?></div>
    			<div class="place"><?= $arExhibition["NAME"]?></div>
    		</div>
    		<div class="content pull-overflow">
    			<div class="pull-left">
    				<div class="pull-overflow mail-list"><a href="<?= $arExhibition["MESSAGES"]["LINK"]?>" title=""><span class="mess"><?= GetMessage("AUTH_P_MESSAGES")?></span> <? if($arExhibition["MESSAGES"]["COUNT"] > 0):?><span class="count pull-right"><?= $arExhibition["MESSAGES"]["COUNT"]?></span><? endif;?></a></div>
    				<div class="pull-overflow mail-list"><a href="<?= $arExhibition["WISHLIST"]["LINK"]?>" title=""><?= GetMessage("AUTH_P_WISHLIST")?></a></div>
    				<div class="pull-overflow mail-list"><a href="<?= $arExhibition["SCHEDULE"]["LINK"]?>" title=""><?= GetMessage("AUTH_P_SHEDULE")?></a></div>
    			</div>
    			<div class="pull-right edit-registration"><a href="<?= $arExhibition["EDIT"]["LINK"]?>" title=""><?= GetMessage("AUTH_P_EDIT_COLLEAGUE")?></a></div>
    		</div>
    	</div>
    <? endforeach;?>
    <?php // ����������������� ?>

    <?php // �� ����������������� ?>
    <? foreach ($arResult["EXHIBITION"]["UNCONFIRMED"] as $arExhibition):?>
    <? if("Sold out" == trim($arExhibition["STATUS"]))
    {
    	continue;
    }
    ?>
    	<div class="gray-grid unregister">
    		<div class="head">
    			<div class="title"><b><?= GetMessage("AUTH_P_CAN_REGISTER")?> <?= $arExhibition["EXH_NAME"]?></b></div>
    			<div class="place"><?= $arExhibition["NAME"]?></div>
    		</div>
    		<div class="content pull-overflow">
    		<?  $exhId = base64_encode(str_code($arExhibition["ID"], "luxoran"));
    		    $userId = base64_encode(str_code($arResult["USER"]["ID"], "luxoran")); ?>
     			<a href="javascript:void(0)" title="<?= GetMessage("AUTH_P_REGISTER_FOR")?> <?= $arExhibition["EXH_NAME"]?>" onclick="regForExhib('<?= $exhId?>','<?= $userId?>','<?= bitrix_sessid()?>')"><?= GetMessage("AUTH_P_REGISTER_FOR")?> <?= $arExhibition["EXH_NAME"]?></a>
    		</div>
    	</div>
    <? endforeach;?>
    <?php // �� ����������������� ?>
<? endif;?>