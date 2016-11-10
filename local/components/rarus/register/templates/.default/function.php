<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


function ShowGroupCheckBox($SID, $NAME, &$arForm)
{
	$Questions = $arForm["QUESTIONS"];
	$Answers = $arForm["ANSWERS"];
	ob_start();
	?>
	<div class="input-select-block">
		<div class="check-group">
			<div class="group-name"><?=$Questions[$SID]["TITLE"]?></div>
			<div class="group-items" id="group-items-<?= randString(5)?>">
				<?foreach ($Answers[$SID] as $arAnswer): ?>
					<label class="check-group" for="check_<?=$SID?>_<?=$arAnswer["ID"]?>" type="checkbox"><?=$arAnswer["MESSAGE"]?></label>
					<input id="check_<?=$SID?>_<?=$arAnswer["ID"]?>" type="checkbox" name="<?=$NAME?>[]" value="<?=$arAnswer["ID"]?>" class = "none" />
				<?endforeach; ?>
			</div>
		</div>
	</div>
<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

function ShowText($SID, $NAME , &$arForm, $CSSClass="", $placeholder="")
{
	$Questions = $arForm["QUESTIONS"];
	$Answers = $arForm["ANSWERS"];
	ob_start();?>
	<div class="input-text-block">
		<input
		 type="text" 
		 name="<?=$NAME?>" 
		 value="" 
		 placeholder="<?=(strlen($placeholder) > 0)?$placeholder:$Questions[$SID]["TITLE"]?>" 
		 <?if(strlen($CSSClass) > 0):?>class="<?=$CSSClass?>"<?endif; ?>
		 />
 	</div>
<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

function ShowPassword($SID,$NAME , &$arForm, $CSSClass="", $placeholder="")
{
	$Questions = $arForm["QUESTIONS"];
	$Answers = $arForm["ANSWERS"];
	ob_start();?>
	<div class="input-text-block">
		<input
		 type="password" 
		 name="<?=$NAME?>" 
		 value="" 
		 placeholder="<?=(strlen($placeholder) > 0)?$placeholder:$Questions[$SID]["TITLE"]?>" 
		 <?if(strlen($CSSClass) > 0):?>class="<?=$CSSClass?>"<?endif; ?>
		 />
 	</div>
<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

function ShowDropDown($SID, $NAME, &$arForm, $placeholder="")
{
	$Questions = $arForm["QUESTIONS"];
	$Dropdown = $arForm["DROPDOWN"];
	ob_start();
	$first = reset($Dropdown[$SID]["reference"]);
	?>
	<? /* <div class="dropdown-title"><?=$Questions[$SID]["TITLE"]?></div> */?>
	<div class="input-select-block">
		<div class="dropdown-group">
			<div class="dropdown-name"><?=(strlen($placeholder) > 0)?$placeholder:$Questions[$SID]["TITLE"]?></div>
			<ul class="dropdown-items" id="dropdown-items-<?= randString(5)?>">
				<?foreach ($Dropdown[$SID]["reference"] as $index => $name): ?>
					<li data-id="<?=$Dropdown[$SID]["reference_id"][$index]?>"><?= $name?></li>
				<?endforeach; ?>
			</ul>
			<select name="<?=$NAME?>" class="none">
				<option disabled selected><?=(strlen($placeholder) > 0)?$placeholder:$Questions[$SID]["TITLE"]?></option>
				<?foreach ($Dropdown[$SID]["reference"] as $index => $name): ?>
				<option value="<?=$Dropdown[$SID]["reference_id"][$index]?>"><?= $name?></option>
				<?endforeach; ?>
			</select>
		</div>
	</div>
<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

function ShowTextArea($SID, $NAME , &$arForm, $CSSClass="", $placeholder="")
{
	$Questions = $arForm["QUESTIONS"];
	$Answers = $arForm["ANSWERS"];
	ob_start();?>
	<div class="input-text-block">
		<textarea 
			name="<?=$NAME?>" 
			placeholder="<?=(strlen($placeholder) > 0)?$placeholder:$Questions[$SID]["TITLE"]?>"   
			<?if(strlen($CSSClass) > 0):?>class="<?=$CSSClass?>"<?endif;?>
		></textarea>
	</div>
	<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

function ShowPriorityAreasCheckBox($SID, $NAME, &$arForm, $textAll)
{
	$Questions = $arForm["QUESTIONS"];
	$Answers = $arForm["ANSWERS"];
	ob_start();
	?>
	<div class="check-priority">
		<div class="priority-name"><?=$Questions[$SID]["TITLE"]?></div>
		<div class="priority-check-all">
			<label class="check-all" for="check_<?=$SID?>_ALL" type="checkbox"><?=$textAll?></label>
			<input id="check_<?=$SID?>_ALL" type="checkbox" name="<?=$NAME?>_ALL" value="" class = "none" />
		</div>
		<div class="priority-items" id="priority-items-<?= randString(5)?>">
			<?foreach ($Answers[$SID] as $arAnswer): ?>
				<label class="check-group" for="check_<?=$SID?>_<?=$arAnswer["ID"]?>" type="checkbox"><?=$arAnswer["MESSAGE"]?></label>
				<input id="check_<?=$SID?>_<?=$arAnswer["ID"]?>" type="checkbox" name="<?=$NAME?>[]" value="<?=$arAnswer["ID"]?>" class = "none" />
			<?endforeach; ?>
		</div>
	</div>
<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

