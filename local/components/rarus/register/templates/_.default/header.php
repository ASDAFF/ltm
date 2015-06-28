<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="exhibition_header"> 		
	<span class="title"><?= GetMessage("R_ABOUT_YOU")?></span>
	<div class="exhibitor"> 			
		<div class="d-title"><?= GetMessage("R_E_TITLE")?></div> 			
		<span class="descr"><?= GetMessage("R_E_DESCRIPTION")?></span> 		
	</div>
 
	<div class="buyer"> 			
		<div class="d-title"><?= GetMessage("R_B_TITLE")?></div> 			
		<span class="descr"><?= GetMessage("R_B_DESCRIPTION")?></span> 		
	</div>
</div>

<div class="clear"></div>

<table class="user-type-select">
	<tbody>
	<tr> 			
		<td> 				
			<label for="radio_PARTICIPANT" class="check <?if($arResult["USER_TYPE"]=="PARTICIPANT"):?>active<?endif ?>"></label> 				
			<input id="radio_PARTICIPANT" type="radio" class="none" value="PARTICIPANT" name="USER_TYPE" <?if($arResult["USER_TYPE"]=="PARTICIPANT"):?>checked="checked"<?endif ?> onClick="UserTypeChange()"/> 			
		</td> 			
		<td> 				
			<label for="radio_BUYER" class="check <?if($arResult["USER_TYPE"]=="BUYER"):?>active<?endif ?>"></label> 				
			<input id="radio_BUYER" type="radio" class="none" value="BUYER" name="USER_TYPE" <?if($arResult["USER_TYPE"]=="BUYER"):?>checked="checked"<?endif ?> onClick="UserTypeChange()"/> 			
			</td> 		
		</tr>
	</tbody>
</table>
<div class="line-sep"></div>