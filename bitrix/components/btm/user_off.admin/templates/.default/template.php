<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
            <div class="main_table">
            <form method="post" action="" name="accept">
             <p class="accept_users"><input name="accept" type="submit" value="����������� �������" /> <a href="/admin/service/excel.php?excel=user_of" style="margin-left:10px;"><input name="excel" type="text" value="������������ Excel" style="cursor:pointer;" /></a></p>
           <table border="0" cellspacing="0" cellpadding="5">
              <tr>
              <?
			  $countPhone = 0;
			  $countName = 0;
              for($i=0; $i<$arResult["FIELDS"]["COUNT"]; $i++){
			  	if($arResult["FIELDS"][$i]["TITLE"] == "�������"){
					if($countPhone == 0){
						?>
						<td width="100"><strong><?=$arResult["FIELDS"][$i]["TITLE"]?></strong></td>
						<?
						$countPhone++;
					}
				}
			  	elseif($arResult["FIELDS"][$i]["TITLE"] == "���" || $arResult["FIELDS"][$i]["TITLE"] == "�������"){
					if($countName == 0){
						?>
						<td width="100"><strong>�������������</strong></td>
						<?
						$countName++;
					}
				}
				elseif($arResult["FIELDS"][$i]["TITLE"] != "������" && strpos($arResult["FIELDS"][$i]["TITLE"], "�������") === false && $arResult["FIELDS"][$i]["TITLE"] != "�������������� e-mail"){
			  	?>
                <td width="100"><strong><?=$arResult["FIELDS"][$i]["TITLE"]?></strong></td>
				<?
				}
			  }
			  ?>
              	<td width="80"><strong>����������� �������</strong></td>
                <td width="80"><strong>�������������</strong></td>
				<td width="80"><strong>��������</strong></td>
                <td width="80"><strong>����</strong></td>
              </tr>
              <?
              for($j=0; $j<$arResult["USERS"]["COUNT"]; $j++){
			  $countPhone = 0;
			  $countName = 0;
			  ?>
              <tr <? if(!($j % 2)){?>class="chet"<? }?>>
              <?
              for($i=0; $i<$arResult["FIELDS"]["COUNT"]; $i++){
			  	if($arResult["FIELDS"][$i]["TITLE"] == "�������"){
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
			  	elseif($arResult["FIELDS"][$i]["TITLE"] == "���" || $arResult["FIELDS"][$i]["TITLE"] == "�������"){
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
				elseif($arResult["FIELDS"][$i]["TITLE"] != "������" && strpos($arResult["FIELDS"][$i]["TITLE"], "�������") === false && $arResult["FIELDS"][$i]["TITLE"] != "�������������� e-mail"){
					$arResult["USERS"][$j]["FIELDS"][$i]=str_replace("�������������� ������� � �����������, �� ������� ������������� ����������","������� � �����������",$arResult["USERS"][$j]["FIELDS"][$i]);
					$arResult["USERS"][$j]["FIELDS"][$i]=str_replace("��������� �������� A","������� �",$arResult["USERS"][$j]["FIELDS"][$i]);
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?></td>
				<?
				}
			  }
			  ?>
              	<td width="80" align="center"><input name="accept[]" type="checkbox" value="<?=$arResult["USERS"][$j]["ID"]?>" /></td>
                <td width="80"><a href="/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit" target="_blank" onclick="newWind('/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit', 500, 600); return false;">�������������</a></td>
                <td width="80"><?=$arResult["USERS"][$j]["FIELDS"][2]?></td>
                <td width="80"><a href="/admin/guest/off/?id=<?=$arResult["USERS"][$j]["ID"]?>&type=spam">����</a></td>
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