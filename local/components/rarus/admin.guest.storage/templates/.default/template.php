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
        <button class="custom-buttom" name="toDelete" style="margin-bottom: 10px;">Удалить (отмеченные записи)</button>
		<div class="filter">
			<form type="GET" action="<?=$arResult['ACTION_URL']?>">
				<input type="text" name="FILTER_DATA" value="<?=$request->get('FILTER_DATA')?>">
				<select name="FILTER_TYPE">
					<option value="UF_EMAIL"
							<? if($request->get('FILTER_TYPE') == 'UF_EMAIL'): ?>selected="selected"<? endif ?>><?=Loc::getMessage('STORAGE_EMAIL')?></option>
					<option value="UF_COMPANY"
							<? if($request->get('FILTER_TYPE') == 'UF_COMPANY'): ?>selected="selected"<? endif ?>><?=Loc::getMessage('STORAGE_WORK_COMPANY')?></option>
					<option value="UF_PHONE"
							<? if($request->get('FILTER_TYPE') == 'UF_PHONE'): ?>selected="selected"<? endif ?>><?=Loc::getMessage('STORAGE_PERSONAL_PHONE')?></option>
					<option value="UF_LOGIN"
							<? if($request->get('FILTER_TYPE') == 'UF_LOGIN'): ?>selected="selected"<? endif ?>><?=Loc::getMessage('STORAGE_LOGIN')?></option>
					<option value="UF_NAME"
							<? if($request->get('FILTER_TYPE') == 'UF_NAME'): ?>selected="selected"<? endif ?>><?=Loc::getMessage('STORAGE_NAME')?></option>
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
				$tableSortableCols = [
					612 => 'UF_COMPANY',
					619 => 'UF_NAME',
					620 => 'UF_SURNAME',
				];
				?>
				<table class="table">
					<thead>
					<tr>
                        <th></th>
						<?$orderSort = 'asc'; $class=""; if('ID' == $arResult["SORT"]) {$class = 'active'; if($arResult['ORDER'] == 'asc') { $orderSort = 'desc';}}?>
						<th><a href="?sort=ID&order=<?=$orderSort;?>" class="sort-title <?=$class?>"><?=Loc::getMessage('STORAGE_ID')?></a></th>
						<?$orderSort = 'asc'; $class=""; if('UF_LOGIN' == $arResult["SORT"]) {$class = 'active'; if($arResult['ORDER'] == 'asc') { $orderSort = 'desc';}}?>
						<th><a href="?sort=UF_LOGIN&order=<?=$orderSort;?>" class="sort-title <?=$class?>"><?=Loc::getMessage('STORAGE_LOGIN')?></a></th>
						<? foreach($arParams["FIELDS"] as $questionID): ?>
							<th>
								<?
								$orderSort = 'asc';
								if($tableSortableCols[$questionID] == $arResult["SORT"] && $arResult["ORDER"] == 'asc'){
									$orderSort = 'desc';
								}
								$class="";
								if($tableSortableCols[$questionID] == $arResult["SORT"]){
									$class = 'active';
								}
								?>
								<? if(array_key_exists($questionID, $tableSortableCols)) {?><a href="?sort=<?=$tableSortableCols[$questionID]?>&order=<?=$orderSort;?>" class="sort-title <?=$class?>"><?}?>
								<?=$arResult['QUESTIONS'][$questionID]['TITLE']?>
								<? if(array_key_exists($questionID, $tableSortableCols)) {?></a><?}?>
							</th>
						<? endforeach; ?>
						<th><?=Loc::getMessage('STORAGE_ACTIONS')?></th>
					</tr>
					</thead>
					<tbody>
					<? $index = 1; ?>
					<? foreach($arResult['USERS'] as $arUser): ?>
						<tr class="<?=(($index++ % 2) != 0) ? "even" : "odd"?>">
                            <td>
                                <input type="checkbox" name="checkToDeleted" value="<?=$arUser['UF_USER_ID']?>">
                            </td>
							<td><?=$arUser['UF_USER_ID']?></td>
							<td><?=$arUser['UF_LOGIN']?></td>
							<? foreach($arParams["FIELDS2"] as $questionID): ?>
								<? $value = $arUser[$questionID]?>
								<td>
									<div class="data-wrap">
										<?=(is_array($value)) ? implode('<br/>', $value) : $value?>
									</div>
								</td>
							<? endforeach; ?>
							<td>
								<div class="action" id="action_<?=$arUser["UF_USER_ID"]?>">
									<img src="/local/templates/admin/images/edit.png">
									<ul class="ul-popup">
										<li><a class="in-working" data-id="<?=$arUser['UF_USER_ID']?>"><?=Loc::getMessage('STORAGE_MOVE')?></a></li>
										<li><a class="to-delete" data-id="<?=$arUser['UF_USER_ID']?>"><?=Loc::getMessage('STORAGE_DELETE')?></a></li>
									</ul>
								</div>
							</td>
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
