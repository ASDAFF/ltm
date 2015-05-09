<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $userId = intval($_REQUEST["UID"]);?>

	<?if (!empty($arResult)):?>
	<ul class="pull-overflow main-tab <?= ($_SESSION["USER_TYPE"] == "GUEST")?"rus":"";?>">
	<?foreach($arResult as $arItem):?>

	<?if ($arItem["PERMISSION"] > "D"):?>
		<li <?=$arItem['SELECTED']?'class="ui-tabs-active"':''?>>
    		<a href="<?=$arItem["LINK"]  . (($userId)?"?UID=" . $userId : "") ?>" title="<?=$arItem["TEXT"]?>" <?= ($arItem["LINK"] == "/members/")?"target='_blank'":"";?>>
    		    <?=$arItem["TEXT"]?>
    		</a>
		</li>
	<?endif?>

<?endforeach?>

	</ul>
	<?endif?>

<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>