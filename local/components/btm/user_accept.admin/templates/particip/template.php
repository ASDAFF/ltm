<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
            <div class="main_table">
            <form method="post" action="" name="accept">
            <p class="accept_users"><input name="accept" type="submit" value="Отменить участие" /> <input name="pay" type="submit" value="Подтвердить оплату" style="margin-left:10px;" /> <a href="/admin/service/excel.php?excel=part_on" style="margin-left:10px;"><input name="excel" type="text" value="Генерировать Excel" style="cursor:pointer;" /></a>  <a href="/admin/service/excel.php?excel=part_all" style="margin-left:10px;"><input name="excel" type="text" value="Excel (все люди)" style="cursor:pointer;" /></a></p>
            <?
            if($arResult["MESSAGE"]){
			?>
			<p><?=$arResult["MESSAGE"];?></p>
			<?
			}
			?>
			<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
            <table border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="50"><strong>ID</strong></td>
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
				elseif($arResult["FIELDS"][$i]["TITLE"] == "Company or Hotel" || $arResult["FIELDS"][$i]["TITLE"] == "Area of business" || $arResult["FIELDS"][$i]["TITLE"] == "City" || $arResult["FIELDS"][$i]["TITLE"] == "Country" || $arResult["FIELDS"][$i]["TITLE"] == "Email" || $arResult["FIELDS"][$i]["TITLE"] == "Telephone number" || $arResult["FIELDS"][$i]["TITLE"] == "Table" || $arResult["FIELDS"][$i]["TITLE"] == "Hall"){
			  	?>
                <td width="80"><strong><?=$arResult["FIELDS"][$i]["TITLE"]?></strong></td>
				<?
				}
			  }
			  ?>
                <td width="90"><strong>Пароль</strong></td>
                <td width="50"><strong>№ счета</strong></td>
                <td width="80"><strong>Сумма счета</strong></td>
                <td width="80"><strong>Выставить счет</strong></td>
                <td width="70"><strong>Оплатил</strong></td>
                <td width="80"><strong>Редактировать</strong></td>
                <td width="80"><strong>Расписание</strong></td>
                <td width="80"><strong>Wish-лист</strong></td>
                <td width="80"><strong>Спам</strong></td>
              	<td width="80"><strong>Отменить участие</strong></td>
              </tr>
              <?
              for($j=0; $j<$arResult["USERS"]["COUNT"]; $j++){
			  ?>
              <tr <? if(!($j % 2)){?>class="chet"<? }?>>
                <td width="50"><?=$arResult["USERS"][$j]["ID"]?></td>
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
				elseif($arResult["FIELDS"][$i]["TITLE"] == "Company or Hotel" || $arResult["FIELDS"][$i]["TITLE"] == "Area of business" || $arResult["FIELDS"][$i]["TITLE"] == "City" || $arResult["FIELDS"][$i]["TITLE"] == "Country" || $arResult["FIELDS"][$i]["TITLE"] == "Email" || $arResult["FIELDS"][$i]["TITLE"] == "Telephone number" || $arResult["FIELDS"][$i]["TITLE"] == "Table" || $arResult["FIELDS"][$i]["TITLE"] == "Hall"){
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?></td>
				<?
				}
			  }
			  ?>
                <td width="90"><?=$arResult["USERS"][$j]["PASS"]?><br />
                <a href="/admin/service/pass.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=1" target="_blank" onclick="newWind('/admin/service/pass.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=1', 500, 260); return false;">Изменить пароль</a></td>
                <td width="50"><?=$arResult["USERS"][$j]["ID"]?></td>
                <td width="80"><?=$arResult["USERS"][$j]["PAY"]?></td>
                <td width="80" align="center"><a href="/admin/service/count.php?id=<?=$arResult["USERS"][$j]["ID"]?>&type=count" target="_blank" onclick="newWind('/admin/service/count.php?id=<?=$arResult["USERS"][$j]["ID"]?>&type=count', 500, 300); return false;">Счет</a></td>
              	<td width="70" align="center">
                <? if($arResult["USERS"][$j]["IS_PAY"]){
					echo 'Оплатил<br /><a href="/admin/particip/on/?id='.$arResult["USERS"][$j]["ID"].'&type=del_pay">Отменить</a>';
                }
                else{
					echo '<input name="pay[]" type="checkbox" value="'.$arResult["USERS"][$j]["ID"].'" />';
				}?>                
                </td>
                <td width="80"><a href="/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit" target="_blank" onclick="newWind('/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit', 500, 600); return false;">Редактировать</a></td>
                <td width="80"><a href="/admin/service/shedule_particip.php?id=<?=$arResult["USERS"][$j]["ID"]?>" target="_blank" onclick="newWind('/admin/service/shedule_particip.php?id=<?=$arResult["USERS"][$j]["ID"]?>', 650, 600); return false;">Генерировать</a></td>
                <td width="80"><a href="/admin/service/wish_particip.php?id=<?=$arResult["USERS"][$j]["ID"]?>" target="_blank" onclick="newWind('/admin/service/wish_particip.php?id=<?=$arResult["USERS"][$j]["ID"]?>', 650, 600); return false;">Генерировать</a></td>
                <td width="80"><a href="/admin/particip/on/?id=<?=$arResult["USERS"][$j]["ID"]?>&type=spam">Спам</a></td>
              	<td width="80" align="center"><a href="/admin/service/decline.php?id=<?=$arResult["USERS"][$j]["ID"]?>" target="_blank" onclick="newWind('/admin/service/decline.php?id=<?=$arResult["USERS"][$j]["ID"]?>', 500, 600); return false;">Отменить</a></td>
              </tr>
              <?
			  }
			  ?>
            </table>
 			<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
            </form>
           </div>
	<?

	//echo "<pre>"; print_r($arResult); echo "</pre>";
}
?>