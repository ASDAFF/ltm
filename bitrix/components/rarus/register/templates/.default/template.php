<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>

<? if($arResult["REGISTER_COMPLETE"] == "Y"):?>
	<?if($arResult["USER_TYPE"]=="BUYER"):?>
		<?= GetMessage("R_B_REGISTER_COMPLETE");?>
	<?else:?>
		<?= GetMessage("R_E_REGISTER_COMPLETE");?>
	<? endif;?>
<? else:?>
<div class="registration">
	<span class="register-title"><?= GetMessage("R_TITLE")?></span>
	<?if($_POST["is_ajax_post"] != "Y")
	{
		?><form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="REGISTER_FORM" id="REGISTER_FORM" enctype="multipart/form-data">
		<?=bitrix_sessid_post()?>
		<div id="register_form_content" class="clearfix">
		<?
	}
	else
	{
		$APPLICATION->RestartBuffer();
	}

	if($arResult["USER_TYPE"]=="BUYER")
	{
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/guest.php");
	}
	else 
	{
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/participant.php");
	}
	
	if($_POST["is_ajax_post"] != "Y")
	{
		?>
			</div>
			<input type="hidden" name="confirmregister" id="confirmregister" value="Y">
			<input type="hidden" name="usertype_change" id="usertype_change" value="N">
			<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
			<?  /* !!!!!!!!! Тут будут кнопки подтверждения регистрации    */?>
			
			<div class="bx_ordercart_order_pay_center"><a href="javascript:void();" onClick="submitForm('Y'); return false;" class="checkout"><?=GetMessage("SOA_TEMPL_BUTTON")?></a></div>
		</form>
		<script type="text/javascript">photoUpload();</script>
		<?
	}
	else
	{
		?>
			<script type="text/javascript">
				top.BX('confirmregister').value = 'Y';
				top.BX('usertype_change').value = 'N';
			</script>
		<?
		die();
	}
	?>

</div>







<script type="text/javascript">
function submitForm(val)
{
	if(val != 'Y')
		BX('confirmregister').value = 'N';

	var orderForm = BX('REGISTER_FORM');

	BX.ajax.submitComponentForm(orderForm, 'register_form_content', true);
	BX.submit(orderForm);

	//$(".validity-tooltip").remove();

	return true;
}


function UserTypeChange(profileId)
{
	BX("usertype_change").value = "Y";
	submitForm();
}
</script>
<? endif;?>
<?php //pre($arResult)?>