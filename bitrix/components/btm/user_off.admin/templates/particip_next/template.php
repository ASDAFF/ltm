<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
            <div class="main_table">
            <form method="post" action="" name="accept">
            <p class="accept_users"><input name="accept" type="submit" value="Подтвердить участие" /> <a href="/admin/service/excel.php?excel=part_of" style="margin-left:10px;"><input name="excel" type="text" value="Генерировать Excel" style="cursor:pointer;" /></a></p>
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
				elseif(strpos($arResult["FIELDS"][$i]["TITLE"], "College") === false && $arResult["FIELDS"][$i]["TITLE"] != "Alternative email" && $arResult["FIELDS"][$i]["TITLE"] != "Table" && $arResult["FIELDS"][$i]["TITLE"] != "Hall"){
			  	?>
                <td width="80"><strong><?=$arResult["FIELDS"][$i]["TITLE"]?></strong></td>
				<?
				}
			  }
			  ?>
				<td width="80"><strong>Источник</strong></td>
				<td width="80"><strong>Company or Hotel</strong></td>
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
				elseif(strpos($arResult["FIELDS"][$i]["TITLE"], "College") === false && $arResult["FIELDS"][$i]["TITLE"] != "Alternative email" && $arResult["FIELDS"][$i]["TITLE"] != "Table" && $arResult["FIELDS"][$i]["TITLE"] != "Hall"){
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?></td>
				<?
				}
			  }
			  ?>
                <td width="80" align="center"><?=$arResult["USERS"][$j]["SOURCE"]?></td>
                <td width="80"><?=$arResult["USERS"][$j]["FIELDS"][0]?></td>
              </tr>
              <?
			  }
			  ?>
            </table>
            </div>
            </form>
	<?

	//echo "<pre>"; print_r($arResult); echo "</pre>";
}
?>