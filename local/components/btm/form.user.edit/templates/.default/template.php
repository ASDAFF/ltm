<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
      <form action="" method="post" name="reg_update">
      <div align="right"><input name="reset" type="reset" value="Reset" class="send_reg" /> <input name="submit" type="submit" value="Submit" class="send_reg" /></div><br />
    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
      <tr class="chet">
        <td width="250"><strong>First name</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_605"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr>
        <td><strong>Last name</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_151"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr class="chet">
        <td><strong>Title</strong></td>
        <td><?
        if($arResult["RESULT"]["SELECT_CHOOSE"]["ANSWER_VALUE"][0]["ANSWER_TEXT"] == "Other" && isset($arResult["RESULT"]["SELECT_CHOOSE_ANS"]["ANSWER_VALUE"][0]["USER_TEXT"])){
			echo $arResult["RESULT"]["select_choose_ans"]["ANSWER_VALUE"][0]["USER_TEXT"];
		}
		else{
			echo $arResult["RESULT"]["SELECT_CHOOSE"]["ANSWER_VALUE"][0]["ANSWER_TEXT"];
		}
        ?></td>
      </tr>
      <tr>
        <td><strong>Company/Hotel</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_961"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr class="chet">
        <td><strong>Area of business</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_716"]["ANSWER_VALUE"][0]["ANSWER_TEXT"]?></td>
      </tr>
      <tr>
        <td><strong>Country</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_876"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr class="chet">
        <td><strong>City</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_653"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr>
        <td><strong>Job title</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_675"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr class="chet">
        <td><strong>E-mail</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_579"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr>
        <td><strong>Alternative e-mail</strong></td>
        <td><? if(isset($arResult["RESULT"]["SIMPLE_QUESTION_662"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo $arResult["RESULT"]["SIMPLE_QUESTION_662"]["ANSWER_VALUE"][0]["USER_TEXT"];}?>&nbsp;</td>
      </tr>
      <tr class="chet">
        <td><strong>Company/Hotel full address</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_700"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr>
        <td><strong>Telephone number</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_250"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr class="chet">
        <td><strong>Company's web-site</strong></td>
        <td><? if(isset($arResult["RESULT"]["SIMPLE_QUESTION_973"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo '<a href="http:\/\/'.$arResult["RESULT"]["SIMPLE_QUESTION_973"]["ANSWER_VALUE"][0]["USER_TEXT"].'">'.$arResult["RESULT"]["SIMPLE_QUESTION_973"]["ANSWER_VALUE"][0]["USER_TEXT"].'</a>';}?>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>Company description</strong></td>
        <td><?=$arResult["RESULT"]["SIMPLE_QUESTION_182"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
    </table>
    <h2 class="reg_title">Colleague</h2>
    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
      <tr class="chet">
        <td width="250"><strong>Title</strong></td>
        <td><?
        if(isset($arResult["RESULT"]["SIMPLE_QUESTION_662"]["ANSWER_VALUE"][0]["USER_TEXT"])){
			if($arResult["RESULT"]["SELECT_CHOOSE1"]["ANSWER_VALUE"][0]["ANSWER_TEXT"] == "Other" && isset($arResult["RESULT"]["SELECT_CHOOSE1_ANS"]["ANSWER_VALUE"][0]["USER_TEXT"])){
				echo $arResult["RESULT"]["SELECT_CHOOSE1_ANS"]["ANSWER_VALUE"][0]["USER_TEXT"];
			}
			else{
				echo $arResult["RESULT"]["SELECT_CHOOSE1"]["ANSWER_VALUE"][0]["ANSWER_TEXT"];
			}
        }
        ?>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>First name</strong></td>
        <td><? if(isset($arResult["RESULT"]["SIMPLE_QUESTION_662"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo $arResult["RESULT"]["SIMPLE_QUESTION_662"]["ANSWER_VALUE"][0]["USER_TEXT"];}?>&nbsp;</td>
      </tr>
      <tr class="chet">
        <td><strong>Last name</strong></td>
        <td><? if(isset($arResult["RESULT"]["SIMPLE_QUESTION_151_far0b"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo $arResult["RESULT"]["SIMPLE_QUESTION_151_far0b"]["ANSWER_VALUE"][0]["USER_TEXT"];}?>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>Job title</strong></td>
        <td><? if(isset($arResult["RESULT"]["SIMPLE_QUESTION_675_Zm0wR"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo $arResult["RESULT"]["SIMPLE_QUESTION_675_Zm0wR"]["ANSWER_VALUE"][0]["USER_TEXT"];}?>&nbsp;</td>
      </tr>
      <tr class="chet">
        <td><strong>E-mail</strong></td>
        <td><? if(isset($arResult["RESULT"]["SIMPLE_QUESTION_579_7Bk0B"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo $arResult["RESULT"]["SIMPLE_QUESTION_579_7Bk0B"]["ANSWER_VALUE"][0]["USER_TEXT"];}?>&nbsp;</td>
      </tr>
    </table>
      <div align="right"><input name="reset" type="reset" value="Reset" class="send_reg" /> <input name="submit" type="submit" value="Submit" class="send_reg" /></div>
      </form>
	<?
}
?>