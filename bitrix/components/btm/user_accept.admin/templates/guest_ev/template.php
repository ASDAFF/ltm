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
            <?
            if(isset($arResult["USERS"]["FORMAT"]) && $arResult["USERS"]["FORMAT"] == 'EVENING'){
				?>
            <p class="accept_users"><a href="/admin/service/excel.php?excel=user_evening&PAGEN_1=1" style="margin-left:10px;"><input name="excel" type="text" value="Генерировать Excel 1" style="cursor:pointer;" /></a>  <a href="/admin/service/excel.php?excel=user_evening&PAGEN_1=2" style="margin-left:10px;"><input name="excel" type="text" value="Генерировать Excel 2" style="cursor:pointer;" /></a><a href="/admin/service/excel.php?excel=user_evening_all&PAGEN_1=1" style="margin-left:10px;"><input name="excel" type="text" value="Excel 1 (все люди)" style="cursor:pointer;" /></a><a href="/admin/service/excel.php?excel=user_evening_all&PAGEN_1=2" style="margin-left:10px;"><input name="excel" type="text" value="Excel 2 (все люди)" style="cursor:pointer;" /></a></p>
				<?
			}
			elseif(isset($arResult["USERS"]["FORMAT"]) && $arResult["USERS"]["FORMAT"] == 'HB'){
				?>
            <p class="accept_users"><a href="/admin/service/excel.php?excel=user_hb" style="margin-left:10px;"><input name="excel" type="text" value="Генерировать Excel" style="cursor:pointer;" /></a>  <a href="/admin/service/excel.php?excel=user_hb_all" style="margin-left:10px;"><input name="excel" type="text" value="Excel (все люди)" style="cursor:pointer;" /></a></p>
				<?
			}
			else{
				?>
            <p class="accept_users"><a href="/admin/service/excel.php?excel=user_morning" style="margin-left:10px;"><input name="excel" type="text" value="Генерировать Excel" style="cursor:pointer;" /></a>  <a href="/admin/service/excel.php?excel=user_morning_all" style="margin-left:10px;"><input name="excel" type="text" value="Excel (все люди)" style="cursor:pointer;" /></a></p>
				<?
			}
			?>
			<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
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
				elseif(strpos($arResult["FIELDS"][$i]["TITLE"], "коллеги (вечер)") !== false){
			  	?>
                <td width="100"><strong><?=$arResult["FIELDS"][$i]["TITLE"]?></strong></td>
				<?
				}
				elseif($arResult["FIELDS"][$i]["TITLE"] != "Гостевое имя" && $arResult["FIELDS"][$i]["TITLE"] != "Пароль" && strpos($arResult["FIELDS"][$i]["TITLE"], "коллеги") === false && $arResult["FIELDS"][$i]["TITLE"] != "Альтернативный e-mail"){
			  	?>
                <td width="100"><strong><?=$arResult["FIELDS"][$i]["TITLE"]?></strong></td>
				<?
				}
			  }
			  ?>
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
				elseif(strpos($arResult["FIELDS"][$i]["TITLE"], "коллеги (вечер)") !== false){
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?></td>
				<?
				}
				elseif($arResult["FIELDS"][$i]["TITLE"] != "Гостевое имя" && $arResult["FIELDS"][$i]["TITLE"] != "Пароль" && strpos($arResult["FIELDS"][$i]["TITLE"], "коллеги") === false && $arResult["FIELDS"][$i]["TITLE"] != "Альтернативный e-mail"){
					$arResult["USERS"][$j]["FIELDS"][$i]=str_replace("Вечерняя сессия (без предварительно назначенных встреч)","Вечерняя сессия",$arResult["USERS"][$j]["FIELDS"][$i]);
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?></td>
				<?
				}
			  }
			  ?>
                <td width="80"><a href="/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit" target="_blank" onclick="newWind('/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit', 500, 600); return false;">Редактировать</a></td>
                <td width="80"><a href="/admin/guest/evening/?id=<?=$arResult["USERS"][$j]["ID"]?>&type=spam">Спам</a></td>
              </tr>
              <?
			  }
			  ?>
            </table>
			<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
            </form>
            <?
			}
			?>
            </div>
	<?

	//echo "<pre>"; print_r($arResult); echo "</pre>";
}
?>