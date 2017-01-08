<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="shedule-info clearfix">
<?if(isset($arResult['ERROR_MESSAGE'])):?>
	<p class="shedule-info__desc"><? echo implode('<br>', $arResult['ERROR_MESSAGE']); ?></p>
	<p class="shedule-info__btn-block">
		<span class="shedule-info__btn shedule-info__ok" href="">OK</span>
	</p>
<?else:?>
	<? if($arResult['TO_RESERVE'] == 'Y'):?>
		<?if($arResult['SEND'] == 'Y'):?>
			<p class="shedule-info__desc">You're reserving timeslot and it can be used for your purpose.</p>
			<p class="shedule-info__btn-block">
				<span class="shedule-info__btn shedule-info__reload" href="">OK</span>
			</p>
		<?else:?>
			<form action="" method="post">
				<input type="hidden" name="app" value="<?=$arResult["APP"]?>">
				<input type="hidden" name="id" value="<?=$arResult["USER_ID"]?>">
				<input type="hidden" name="time" value="<?=$arResult["TIME"]?>">
				<input type="hidden" name="confirm" value="Y">
				<input type="hidden" name="exib_code" value="<?=$arResult["EXIB_CODE"]?>">
				<input type="hidden" name="href" value="<?=$APPLICATION->GetCurUri()?>&confirm=Y">
				<p class="shedule-info__desc">Selected timeslot will be reserved for your purpose and won't be available to make appointment request until you release it.</p>
				<p class="shedule-info__btn-block">
					<span class="shedule-info__btn shedule-info__close">Cancel</span>
					<span class="shedule-info__btn shedule-info__send">OK</span>
				</p>
			</form>
		<?endif;?>
	<?else:?>
		<p class="shedule-info__desc">You're releasing timeslot and it can be used for to make an appointment.</p>
		<p class="shedule-info__btn-block">
			<span class="shedule-info__btn shedule-info__ok shedule-info__reload" href="">OK</span>
		</p>
	<?endif?>
<?endif;?>
</div>