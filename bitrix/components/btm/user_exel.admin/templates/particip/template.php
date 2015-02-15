<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
            <div class="main_table">
            <form method="post" action="" name="accept">
            <p class="accept_users"><input name="accept" type="submit" value="Отменить участие" /></p>
            <?
            if($arResult["MESSAGE"]){
			?>
			<p><?=$arResult["MESSAGE"];?></p>
			<?
			}
			?>
            <table border="0" cellspacing="0" cellpadding="5">
              <tr>
              <?
			  $countPhone = 0;
			  $countName = 0;
              for($i=0; $i<$arResult["FIELDS"]["COUNT"]; $i++){
			  	if($arResult["FIELDS"][$i]["TITLE"] == "First Name" || $arResult["FIELDS"][$i]["TITLE"] == "Last Name"){
					if($countName == 0){
						?>
						<td width="80"><strong>Representative</strong></td>
						<?
						$countName++;
					}
				}
				elseif($arResult["FIELDS"][$i]["TITLE"] == "Company or Hotel" || $arResult["FIELDS"][$i]["TITLE"] == "Email" || $arResult["FIELDS"][$i]["TITLE"] == "Telephone number" || $arResult["FIELDS"][$i]["TITLE"] == "Table" || $arResult["FIELDS"][$i]["TITLE"] == "Hall"){
			  	?>
                <td width="80"><strong><?=$arResult["FIELDS"][$i]["TITLE"]?></strong></td>
				<?
				}
			  }
			  ?>
                <td width="80"><strong>Пароль</strong></td>
                <td width="80"><strong>Сумма счета</strong></td>
                <td width="80"><strong>Выставить счет</strong></td>
                <td width="80"><strong>Редактировать</strong></td>
                <td width="80"><strong>Спам</strong></td>
              	<td width="80"><strong>Отменить участие</strong></td>
              </tr>
              <?
              for($j=0; $j<$arResult["USERS"]["COUNT"]; $j++){
			  ?>
              <tr <? if(!($j % 2)){?>class="chet"<? }?>>
              <?
			  $countPhone = 0;
			  $countName = 0;
              for($i=0; $i<$arResult["FIELDS"]["COUNT"]; $i++){
			  	if($arResult["FIELDS"][$i]["TITLE"] == "First Name" || $arResult["FIELDS"][$i]["TITLE"] == "Last Name"){
					if($countName == 0){
						?>
						<td width="100"><?=$arResult["USERS"][$j]["FIELDS"][$i]?> 
						<?
						$countName++;
					}
					else{
						?>
						 <?=$arResult["USERS"][$j]["FIELDS"][$i]?></td>
						<?
						$countName++;
					}
				}
				elseif($arResult["FIELDS"][$i]["TITLE"] == "Company or Hotel" || $arResult["FIELDS"][$i]["TITLE"] == "Email" || $arResult["FIELDS"][$i]["TITLE"] == "Telephone number" || $arResult["FIELDS"][$i]["TITLE"] == "Table" || $arResult["FIELDS"][$i]["TITLE"] == "Hall"){
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?></td>
				<?
				}
			  }
			  ?>
                <td width="80"><?=$arResult["USERS"][$j]["PASS"]?></td>
                <td width="80"><?=$arResult["USERS"][$j]["PAY"]?></td>
                <td width="80" align="center"><a href="/admin/service/count.php?id=<?=$arResult["USERS"][$j]["ID"]?>&type=count" target="_blank" onclick="newWind('/admin/service/count.php?id=<?=$arResult["USERS"][$j]["ID"]?>&type=count', 500, 300); return false;">Счет</a></td>
                <td width="80"><a href="/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit" target="_blank" onclick="newWind('/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit', 500, 600); return false;">Редактировать</a></td>
                <td width="80"><a href="/admin/particip/off/?id=<?=$arResult["USERS"][$j]["ID"]?>&type=spam">Спам</a></td>
              	<td width="80" align="center"><input name="accept[]" type="checkbox" value="<?=$arResult["USERS"][$j]["ID"]?>" /></td>
              </tr>
              <?
			  }
			  ?>
            </table>
            </form>
            </div>
	<?

	//echo "<pre>"; print_r($arResult); echo "</pre>";
}
?>