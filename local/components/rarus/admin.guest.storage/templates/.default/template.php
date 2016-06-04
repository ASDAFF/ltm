<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use \Bitrix\Main\Localization\Loc;

$request = \Bitrix\Main\HttpContext::getCurrent()->getRequest();
?>

<? if(!empty($arResult["USERS"]) || $request->get('filter')): ?>
	<div class="storage">
		<div class="filter">
			<form type="GET" action="<?=$arResult['ACTION_URL']?>">
				<input type="text" name="FILTER_DATA" value="<?=$request->get('FILTER_DATA')?>">
				<select name="FILTER_TYPE">
					<option value="EMAIL"
							<? if($request->get('FILTER_TYPE') == 'EMAIL'): ?>selected="selected"<? endif ?>><?=Loc::getMessage('STORAGE_EMAIL')?></option>
					<option value="WORK_COMPANY"
							<? if($request->get('FILTER_TYPE') == 'WORK_COMPANY'): ?>selected="selected"<? endif ?>><?=Loc::getMessage('STORAGE_WORK_COMPANY')?></option>
					<option value="PERSONAL_PHONE"
							<? if($request->get('FILTER_TYPE') == 'PERSONAL_PHONE'): ?>selected="selected"<? endif ?>><?=Loc::getMessage('STORAGE_PERSONAL_PHONE')?></option>
					<option value="LOGIN"
							<? if($request->get('FILTER_TYPE') == 'LOGIN'): ?>selected="selected"<? endif ?>><?=Loc::getMessage('STORAGE_LOGIN')?></option>
					<option value="NAME"
							<? if($request->get('FILTER_TYPE') == 'NAME'): ?>selected="selected"<? endif ?>><?=Loc::getMessage('STORAGE_NAME')?></option>
				</select>
				<input type="submit" value="<?=Loc::getMessage('STORAGE_SEARCH')?>" name="filter">
				<input type="submit" value="<?=Loc::getMessage('STORAGE_RESET')?>" name="reset_filter">
			</form>
		</div>
		<? if(!empty($arResult["USERS"])): ?>
			<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
			<div class="table-responsive">
				<?
				if(!empty($component->errors)){
					ShowError(implode('<br/>', $component->errors));
				}
				?>
				<table class="table">
					<thead>
					<tr>
						<th><?=Loc::getMessage('STORAGE_ID')?></th>
						<th><?=Loc::getMessage('STORAGE_LOGIN')?></th>
						<? foreach($arParams["FIELDS"] as $questionID): ?>
							<th><?=$arResult['QUESTIONS'][$questionID]['TITLE']?></th>
						<? endforeach; ?>
						<th><?=Loc::getMessage('STORAGE_ACTIONS')?></th>
					</tr>
					</thead>
					<tbody>
					<? $index = 1; ?>
					<? foreach($arResult['USERS'] as $arUser): ?>
						<tr class="<?=(($index++ % 2) != 0) ? "even" : "odd"?>">
							<td><?=$arUser['ID']?></td>
							<td><?=$arUser['LOGIN']?></td>
							<? foreach($arParams["FIELDS"] as $questionID): ?>
								<? $value = $arUser['FORM_DATA'][$questionID]['VALUE'] ?>
								<td>
									<div class="data-wrap">
										<?=(is_array($value)) ? implode('<br/>', $value) : $value?>
									</div>
								</td>
							<? endforeach; ?>
							<td><a class="in-working"
								   data-id="<?=$arUser['ID']?>"><?=Loc::getMessage('STORAGE_MOVE')?></a></td>
						</tr>
					<? endforeach; ?>
					</tbody>
				</table>

			</div>
		<? else: ?>
			<?=Loc::getMessage("STORAGE_NO_GUESTS_FOUND")?>
		<? endif; ?>
	</div>
<? else: ?>
	<?=Loc::getMessage("STORAGE_NO_GUESTS")?>
<? endif; ?>
