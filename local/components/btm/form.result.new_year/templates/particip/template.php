<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	if(isset($arResult["MESSAGE"]) && $arResult["MESSAGE"] != ''){
		echo '<p class="error">'.$arResult["MESSAGE"].'</p>';
	}
	?>
    <script type="text/javascript">
		$(document).ready(function(){
		   $("select").change(function () {
				$changeDiv = $("div."+$(this).attr("name")+"_other");
				if($("option:selected",this).text() == 'Other'){
					$changeDiv.show();
				}
				else{
					$changeDiv.hide();
				}
			})
		});	
    </script>
    <?
    if($arResult["ACTION_TYPE"] == "EDITE"){
		?>
      <form action="" method="post" name="reg_update">
        <div align="right"><input name="reset" type="reset" value="Reset" class="send_reg" /> <input name="submit" type="submit" value="Submit" class="send_reg" /></div><br />
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
          <tr class="chet">
            <td width="250"><strong>First name</strong></td>
            <td><input name="SIMPLE_QUESTION_605" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_605"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_605" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_605"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Last name</strong></td>
            <td><input name="SIMPLE_QUESTION_151" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_151"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_151" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_151"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Title</strong></td>
            <td>
            <select name="select_choose">
            	<?
				foreach($arResult["QUEST"]["select_choose"]["ANSWER_ARR"] as $keyField => $optField){
					?>
	                  <option value="<?=$optField["ID"]?>" <? if($optField["ID"] == $arResult["QUEST"]["select_choose"]["ANSWER_ID"]){ echo 'selected="selected"';}?>><?=$optField["MESSAGE"]?></option>
					<?
				}
				?>
            </select><br />
            <div <? if($arResult["QUEST"]["select_choose"]["VALUE"] == 'Other'){ echo 'display:block;';}?> class="select_choose_other">
                <input name="select_choose_ans" type="text" value="<?=$arResult["QUEST"]["select_choose_ans"]["VALUE"]?>" style="margin-top:2px;" />
            </div>
            <input name="OLD_select_choose" type="hidden" value="<?=$arResult["QUEST"]["select_choose"]["VALUE"]?>" />
            </td>
          </tr>
          <tr>
            <td><strong>Company/Hotel</strong></td>
            <td><input name="company" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_961"]["VALUE"]?>" readonly="readonly" style="background:#999999"/><input name="OLD_SIMPLE_QUESTION_961" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_961"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Area of business</strong></td>
            <td><input name="area" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_716"]["VALUE"]?>" readonly="readonly" style="background:#999999"/><input name="OLD_SIMPLE_QUESTION_716" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_716"]["VALUE"]?>" />
            </td>
          </tr>
          <tr>
            <td><strong>Country</strong></td>
            <td><input name="country" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_876"]["VALUE"]?>" readonly="readonly" style="background:#999999"/><input name="OLD_SIMPLE_QUESTION_876" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_876"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>City</strong></td>
            <td><input name="SIMPLE_QUESTION_653" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_653"]["VALUE"]?>" readonly="readonly" style="background:#999999" /><input name="OLD_SIMPLE_QUESTION_653" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_653"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Job title</strong></td>
            <td><input name="SIMPLE_QUESTION_675" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_675"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_675" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_675"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>E-mail</strong></td>
            <td><input name="SIMPLE_QUESTION_579" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_579"]["VALUE"]?>" readonly="readonly" style="background:#999999" /><input name="OLD_SIMPLE_QUESTION_579" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_579"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Alternative e-mail</strong></td>
            <td><input name="SIMPLE_QUESTION_662" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_662"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_662" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_662"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Company/Hotel full address</strong></td>
            <td><input name="SIMPLE_QUESTION_700" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_700"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_700" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_700"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Telephone number</strong></td>
            <td><input name="SIMPLE_QUESTION_250" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_250"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_250" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_250"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Company's web-site</strong></td>
            <td><input name="SIMPLE_QUESTION_973" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_973"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_973" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_973"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Company description</strong></td>
            <td><textarea name="SIMPLE_QUESTION_182"><?=$arResult["QUEST"]["SIMPLE_QUESTION_182"]["VALUE"]?></textarea><input name="OLD_SIMPLE_QUESTION_182" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_182"]["VALUE"]?>" /></td>
          </tr>
        </table>
        <h2 class="reg_title">Colleague</h2>
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
          <tr class="chet">
            <td width="250"><strong>Title</strong></td>
            <td>
            <select name="select_choose1">
            	<?
				foreach($arResult["QUEST"]["select_choose1"]["ANSWER_ARR"] as $keyField => $optField){
					?>
	                  <option value="<?=$optField["ID"]?>" <? if($optField["ID"] == $arResult["QUEST"]["select_choose1"]["ANSWER_ID"]){ echo 'selected="selected"';}?>><?=$optField["MESSAGE"]?></option>
					<?
				}
				?>
            </select><br />
            <div <? if($arResult["QUEST"]["select_choose1"]["VALUE"] == 'Other'){ echo 'display:block;';}?> class="select_choose1_other">
                <input name="select_choose1_ans" type="text" value="<?=$arResult["QUEST"]["select_choose1_ans"]["VALUE"]?>" style="margin-top:2px;" />
            </div>
            <input name="OLD_select_choose1" type="hidden" value="<?=$arResult["QUEST"]["select_choose1"]["VALUE"]?>" />
            </td>
          </tr>
          <tr>
            <td><strong>First name</strong></td>
            <td><input name="SIMPLE_QUESTION_605_zway4" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_605_zway4"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_605_zway4" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_605_zway4"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Last name</strong></td>
            <td><input name="SIMPLE_QUESTION_151_far0b" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_151_far0b"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_151_far0b" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_151_far0b"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Job title</strong></td>
            <td><input name="SIMPLE_QUESTION_675_Zm0wR" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_675_Zm0wR"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_675_Zm0wR" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_675_Zm0wR"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>E-mail</strong></td>
            <td><input name="SIMPLE_QUESTION_579_7Bk0B" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_579_7Bk0B"]["VALUE"]?>" /><input name="OLD_SIMPLE_QUESTION_579_7Bk0B" type="hidden" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_579_7Bk0B"]["VALUE"]?>" /></td>
          </tr>
        </table>
        <input name="usact" type="hidden" value="update" />
        <div align="right"><input name="reset" type="reset" value="Reset" class="send_reg" /> <input name="submit" type="submit" value="Submit" class="send_reg" /></div>
        </form>
		<?
	}
	else{
	?>
      <form action="" method="post" name="reg_update">
        <div align="right"><input name="reset" type="reset" value="Reset" class="send_reg" /> <input name="submit" type="submit" value="Submit" class="send_reg" /></div><br />
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
          <tr class="chet">
            <td width="250"><strong>First name</strong></td>
            <td><input name="SIMPLE_QUESTION_605" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_605"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Last name</strong></td>
            <td><input name="SIMPLE_QUESTION_151" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_151"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Title</strong></td>
            <td>
            <select name="select_choose">
            	<?
				foreach($arResult["QUEST"]["select_choose"]["ANSWER_ARR"] as $keyField => $optField){
					?>
	                  <option value="<?=$optField["ID"]?>" <? if($optField["ID"] == $arResult["QUEST"]["select_choose"]["ANSWER_ID"]){ echo 'selected="selected"';}?>><?=$optField["MESSAGE"]?></option>
					<?
				}
				?>
            </select><br />
            <div <? if($arResult["QUEST"]["select_choose"]["VALUE"] == 'Other'){ echo 'display:block;';}?> class="select_choose_other">
                <input name="select_choose_ans" type="text" value="<?=$arResult["QUEST"]["select_choose_ans"]["VALUE"]?>" style="margin-top:2px;" />
            </div>
            <input name="OLD_select_choose" type="hidden" value="<?=$arResult["QUEST"]["select_choose"]["VALUE"]?>" />
            </td>
          </tr>
          <tr>
            <td><strong>Company/Hotel</strong></td>
            <td><input name="SIMPLE_QUESTION_961" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_961"]["VALUE"]?>"/></td>
          </tr>
          <tr class="chet">
            <td><strong>Area of business</strong></td>
            <td><select name="SIMPLE_QUESTION_716">
            	<?
				foreach($arResult["QUEST"]["SIMPLE_QUESTION_716"]["ANSWER_ARR"] as $keyField => $optField){
					?>
	                  <option value="<?=$optField["ID"]?>" <? if($optField["ID"] == $arResult["QUEST"]["SIMPLE_QUESTION_716"]["ANSWER_ID"]){ echo 'selected="selected"';}?>><?=$optField["MESSAGE"]?></option>
					<?
				}
				?>
            </td>
          </tr> 
          <tr> 
            <td><strong>Country</strong></td>
            <td><input name="SIMPLE_QUESTION_876" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_876"]["VALUE"]?>"/></td>
          </tr> 
          <tr class="chet"> 
            <td><strong>City</strong></td>
            <td><input name="SIMPLE_QUESTION_653" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_653"]["VALUE"]?>" /></td>
          </tr> 
          <tr> 
            <td><strong>Job title</strong></td>
            <td> <input name="SIMPLE_QUESTION_675" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_675"]["VALUE"]?>" /></td>
          </tr> 
          <tr class="chet"> 
            <td><strong>E-mail</strong></td>
            <td><input name="SIMPLE_QUESTION_579" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_579"]["VALUE"]?>" /></td>
          </tr> 
          <tr> 
            <td><strong>Alternative e-mail</strong></td>
            <td><input name="SIMPLE_QUESTION_662" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_662"]["VALUE"]?>" /></td>
          </tr> 
          <tr class="chet"> 
            <td><strong>Company/Hotel full address</strong></td>
            <td><input name="SIMPLE_QUESTION_700" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_700"]["VALUE"]?>" /></td>
          </tr> 
          <tr> 
            <td> <strong>Telephone number</strong></td>
            <td> <input name="SIMPLE_QUESTION_250" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_250"]["VALUE"]?>" /></td>
          </tr> 
          <tr class="chet"> 
            <td><strong>Company's web-site</strong></td>
            <td><input name="SIMPLE_QUESTION_973" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_973"]["VALUE"]?>" /></td>
          </tr> 
          <tr> 
            <td> <strong>Company description</strong></td>
            <td><textarea name="SIMPLE_QUESTION_182"><?=$arResult["QUEST"]["SIMPLE_QUESTION_182"]["VALUE"]?></textarea></td>
          </tr> 
        </table>
        <h2 class="reg_title">Colleague</h2>
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
          <tr class="chet">
            <td width="250"><strong>Title</strong></td>
            <td>
            <select name="select_choose1">
            	<?
				foreach($arResult["QUEST"]["select_choose1"]["ANSWER_ARR"] as $keyField => $optField){
					print_r($optField);
					?>
	                  <option value="<?=$optField["ID"]?>" <? if($optField["ID"] == $arResult["QUEST"]["select_choose1"]["ANSWER_ID"]){ echo 'selected="selected"';}?>><?=$optField["MESSAGE"]?></option>
					<?
				}
				?>
            </select><br />
            <div <? if($arResult["QUEST"]["select_choose1"]["VALUE"] == 'Other'){ echo 'display:block;';}?> class="select_choose1_other">
                <input name="select_choose1_ans" type="text" value="<?=$arResult["QUEST"]["select_choose1_ans"]["VALUE"]?>" style="margin-top:2px;" />
            </div>
            <input name="OLD_select_choose1" type="hidden" value="<?=$arResult["QUEST"]["select_choose1"]["VALUE"]?>" />
            </td>
          </tr>
          <tr>
            <td><strong>First name</strong></td>
            <td><input name="SIMPLE_QUESTION_662" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_662"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Last name</strong></td>
            <td><input name="SIMPLE_QUESTION_151_far0b" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_151_far0b"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Job title</strong></td>
            <td><input name="SIMPLE_QUESTION_675_Zm0wR" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_675_Zm0wR"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>E-mail</strong></td>
            <td><input name="SIMPLE_QUESTION_579_7Bk0B" type="text" value="<?=$arResult["QUEST"]["SIMPLE_QUESTION_579_7Bk0B"]["VALUE"]?>" /></td>
          </tr>
        </table>
        <input name="usact" type="hidden" value="update" />
        <div align="right"><input name="reset" type="reset" value="Reset" class="send_reg" /> <input name="submit" type="submit" value="Submit" class="send_reg" /></div>
        </form>
	<?
	}
}
?>