<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
            <div class="main_table">
            <?
            if($arResult["MESSAGE"]){
			?>
			<p><?=$arResult["MESSAGE"];?></p>
			<?
			}
            if($arResult["USERS"]["COUNT"] == 0){
				echo "<p style='font-size:14px;'><strong>В данной категории нет ни одного гостя.</strong></p>\n";
            }
			else{
			?>
            <form method="post" action="" name="accept">
            <p class="accept_users"><input name="accept" type="submit" value="Подтвердить участие" /> <a href="/admin/service/excel.php?excel=user_of" style="margin-left:10px;"><input name="excel" type="text" value="Генерировать Excel" style="cursor:pointer;" /></a></p>
            <table border="0" cellspacing="0" cellpadding="5">
              <tr>
              <?
			  $countPhone = 0;
			  $countName = 0;
              for($i=0; $i<$arResult["FIELDS"]["COUNT"]; $i++){
			  	if($arResult["FIELDS"][$i]["TITLE"] == "Телефон"){
					if($countPhone == 0){
						?>
						<td width="100"><strong><?=$arResult["FIELDS"][$i]["TITLE"]?></strong></td>
						<?
						$countPhone++;
					}
				}
			  	elseif($arResult["FIELDS"][$i]["TITLE"] == "Имя" || $arResult["FIELDS"][$i]["TITLE"] == "Фамилия"){
					if($countName == 0){
						?>
						<td width="100"><strong>Представитель</strong></td>
						<?
						$countName++;
					}
				}
				elseif($arResult["FIELDS"][$i]["TITLE"] != "Пароль" && strpos($arResult["FIELDS"][$i]["TITLE"], "коллеги") === false && $arResult["FIELDS"][$i]["TITLE"] != "Альтернативный e-mail"){
			  	?>
                <td width="100"><strong><?=$arResult["FIELDS"][$i]["TITLE"]?></strong></td>
				<?
				}
			  }
			  ?>
              	<td width="80"><strong>Подтвердить утро</strong></td>
              	<td width="80"><strong>Подтвердить вечер</strong></td>
              	<td width="80"><strong>Подтвердить HB</strong></td>
                <td width="80"><strong>Редактировать</strong></td>
                <td width="80"><strong>Спам</strong></td>
              </tr>
              <?
              for($j=0; $j<$arResult["USERS"]["COUNT"]; $j++){
			  $countPhone = 0;
			  $countName = 0;
			  ?>
              <tr <? if(!($j % 2)){?>class="chet"<? }?>>
              <?
              for($i=0; $i<$arResult["FIELDS"]["COUNT"]; $i++){
			  	if($arResult["FIELDS"][$i]["TITLE"] == "Телефон"){
					if($countPhone == 0){
						?>
						<td><nobr><?=$arResult["USERS"][$j]["FIELDS"][$i]?> 
						<?
						$countPhone++;
					}
					elseif($countPhone==2){
						?>
						 <?=$arResult["USERS"][$j]["FIELDS"][$i]?></nobr></td>
						<?
						$countPhone++;
					}
					else{
						?>
						 (<?=$arResult["USERS"][$j]["FIELDS"][$i]?>)
						<?
						$countPhone++;
					}
				}
			  	elseif($arResult["FIELDS"][$i]["TITLE"] == "Имя" || $arResult["FIELDS"][$i]["TITLE"] == "Фамилия"){
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
				elseif($arResult["FIELDS"][$i]["TITLE"] == "Форма посещения"){
					$arResult["USERS"][$j]["FIELDS"][$i]=str_replace("Вечерняя сессия (18:30 – 21:30, без заранее назначенных встреч с участниками)","Вечерняя сессия",$arResult["USERS"][$j]["FIELDS"][$i]);
                                        $arResult["USERS"][$j]["FIELDS"][$i]=str_replace("Утренняя сессия (10:00 – 14:30, с заранее назначенными встречами с участниками)","Утренняя сессия",$arResult["USERS"][$j]["FIELDS"][$i]);
					if($arResult["USERS"][$j]["FIELDS"][$i] == "Вечерняя сессия"){
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?><input name="evening<?=$arResult["USERS"][$j]["ID"]?>" type="hidden" value="1" /></td>
				<?
					}
					elseif($arResult["USERS"][$j]["FIELDS"][$i] == "Утренняя сессия"){
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?><input name="morning<?=$arResult["USERS"][$j]["ID"]?>" type="hidden" value="1" /></td>
				<?
					}
					else{
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?><input name="evening<?=$arResult["USERS"][$j]["ID"]?>" type="hidden" value="1" /><input name="morning<?=$arResult["USERS"][$j]["ID"]?>" type="hidden" value="1" /></td>
				<?
					}
				}
				elseif($arResult["FIELDS"][$i]["TITLE"] == "E-mail"){
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?><input name="email<?=$arResult["USERS"][$j]["ID"]?>" type="hidden" value="<?=$arResult["USERS"][$j]["FIELDS"][$i]?>" /></td>
				<?
				}
				elseif($arResult["FIELDS"][$i]["TITLE"] != "Пароль" && strpos($arResult["FIELDS"][$i]["TITLE"], "коллеги") === false && $arResult["FIELDS"][$i]["TITLE"] != "Альтернативный e-mail"){
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?></td>
				<?
				}
			  }
			  ?>
              	<td width="80" align="center"><input name="morning[]" type="checkbox" value="<?=$arResult["USERS"][$j]["ID"]?>" /></td>
              	<td width="80" align="center"><input name="evning[]" type="checkbox" value="<?=$arResult["USERS"][$j]["ID"]?>" /></td>
              	<td width="80" align="center"><input name="hb[]" type="checkbox" value="<?=$arResult["USERS"][$j]["ID"]?>" /></td>
                <td width="80"><a href="/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit" target="_blank" onclick="newWind('/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit', 500, 600); return false;">Редактировать</a></td>
                <td width="80"><a href="/admin/guest/off/?id=<?=$arResult["USERS"][$j]["ID"]?>&type=spam">Спам</a></td>
              </tr>
              <?
			  }
			  ?>
            </table>
            </form>
            <?
			}
			?>
            </div>
	<?

	//echo "<pre>"; print_r($arResult); echo "</pre>";
}
?>