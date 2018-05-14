<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


function ShowGroupCheckBox($arrField, $NAME, $title = "")
{
	ob_start();
	?>
	<div class="input-select-block">
		<div class="check-group">
			<div class="group-name"><?=$title?:$arrField["EDIT_FORM_LABEL"]?></div>
			<div class="group-items" id="group-items-<?= randString(5)?>">
				<?foreach ($arrField['ITEMS'] as $item): ?>
					<label class="check-group" for="check_<?=$NAME?>_<?=$item["ID"]?>" type="checkbox"><?=$item["UF_VALUE"]?></label>
					<input id="check_<?=$NAME?>_<?=$item["ID"]?>" type="checkbox" name="<?=$NAME?>[]" value="<?=$item["ID"]?>" class = "none" />
				<?endforeach; ?>
			</div>
		</div>
	</div>
<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

function ShowText($arrField, $NAME , $CSSClass="", $placeholder="")
{
	ob_start();?>
	<div class="input-text-block">
		<input
		 type="text" 
		 name="<?=$NAME?>" 
		 value="" 
		 placeholder="<?=(strlen($placeholder) > 0)?$placeholder:$arrField["EDIT_FORM_LABEL"]?>"
		 <?if(strlen($CSSClass) > 0):?>class="<?=$CSSClass?>"<?endif; ?>
		 />
 	</div>
<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

function ShowPassword($arrField,$NAME , $CSSClass="", $placeholder="")
{
	ob_start();?>
	<div class="input-text-block">
		<input
		 type="password" 
		 name="<?=$NAME?>" 
		 value="" 
		 placeholder="<?=(strlen($placeholder) > 0)?$placeholder:$arrField["EDIT_FORM_LABEL"]?>"
		 <?if(strlen($CSSClass) > 0):?>class="<?=$CSSClass?>"<?endif; ?>
		 />
 	</div>
<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

function ShowDropDown($arrField, $NAME, $placeholder="")
{
	ob_start();
	?>
	<div class="input-select-block">
		<div class="dropdown-group">
			<div class="dropdown-name"><?=(strlen($placeholder) > 0)?$placeholder:$arrField["EDIT_FORM_LABEL"]?></div>
			<ul class="dropdown-items" id="dropdown-items-<?= randString(5)?>">
				<?foreach ($arrField["ITEMS"] as $item): ?>
					<?if($item['UF_VALUE'] != 'None'):?>
						<li data-id="<?=$item["ID"]?>"><?= $item['UF_VALUE']?></li>
					<?endif;?>
				<?endforeach; ?>
			</ul>
			<select name="<?=$NAME?>" class="none">
				<option disabled selected><?=(strlen($placeholder) > 0)?$placeholder:$arrField["EDIT_FORM_LABEL"]?></option>
				<?foreach ($arrField["ITEMS"] as $item): ?>
					<?if($item['UF_VALUE'] != 'None'):?>
						<option value="<?=$item["ID"]?>"><?= $item['UF_VALUE']?></option>
					<?endif;?>
				<?endforeach; ?>
			</select>
		</div>
	</div>
<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

function ShowTextArea($arrField, $NAME, $CSSClass="", $placeholder="")
{
	ob_start();?>
	<div class="input-text-block">
		<textarea 
			name="<?=$NAME?>" 
			placeholder="<?=(strlen($placeholder) > 0)?$placeholder:$arrField["EDIT_FORM_LABEL"]?>"
			<?if(strlen($CSSClass) > 0):?>class="<?=$CSSClass?>"<?endif;?>
		></textarea>
	</div>
	<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

function ShowPriorityAreasCheckBox($arrField, $NAME, $textAll)
{
	ob_start();
	?>
	<div class="check-priority">
		<div class="priority-name"><?=$arrField["EDIT_FORM_LABEL"]?></div>
		<div class="priority-check-all">
			<label class="check-all" for="check_<?=$NAME?>_ALL" type="checkbox"><?=$textAll?></label>
			<input id="check_<?=$NAME?>_ALL" type="checkbox" name="<?=$NAME?>_ALL" value="" class = "none" />
		</div>
		<div class="priority-items" id="priority-items-<?= randString(5)?>">
			<?foreach ($arrField['ITEMS'] as $item): ?>
				<label class="check-group" for="check_<?=$NAME?>_<?=$item["ID"]?>" type="checkbox"><?=$item["UF_VALUE"]?></label>
				<input id="check_<?=$NAME?>_<?=$item["ID"]?>" type="checkbox" name="<?=$NAME?>[]" value="<?=$item["ID"]?>" class = "none" />
			<?endforeach; ?>
		</div>
	</div>
<?
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}

