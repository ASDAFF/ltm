<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?if(!$arResult['IS_ACTIVE'] && $arResult['USER_TYPE'] != 'ADMIN'):?>
	<?echo GetMessage($arResult['USER_TYPE'] . '_EXHIBITION_BLOCKED');?>
<?elseif(isset($arResult['ERROR_MESSAGE'])):?>
	<? echo implode('<br>', $arResult['ERROR_MESSAGE']); ?>
<?elseif(isset($arResult['REQUEST_SENT'])):?>
	<? if (isset($arResult['FORM_ERROR'])): ?>
		РћС€РёР±РєР° РѕС‚РїСЂР°РІРєРё. РЎР»РѕС‚ Р·Р°РЅСЏС‚.
	<? else: ?>
		Р—Р°РїСЂРѕСЃ СѓСЃРїРµС€РЅРѕ РѕС‚РїСЂР°РІР»РµРЅ. Р—Р°РєСЂС‹С‚РёРµ С‡РµСЂРµР· 5 СЃРµРєСѓРЅРґ.
	<? endif;?>
	<script type="text/javascript">
		setTimeout( function() { window.close(); }, 5000);
	</script>
<?else:?>
	<?
	switch ($arResult['USER_TYPE']) {
		case 'ADMIN':
		case 'PARTICIP':
			include_once(dirname(__FILE__) . '/particip.php');
			break;
		case 'GUEST':
			include_once(dirname(__FILE__) . '/' . strtolower($arResult['USER_TYPE']));
	}
	?>
<?endif;?>
<?
var_dump($arResult);
?>