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
				echo "<p style='font-size:14px;'><strong>� ������ ��������� ��� �� ������ �����.</strong></p>\n";
            }
			else{
			?>
            <form method="post" action="" name="accept">
            <?
            if(isset($arResult["USERS"]["FORMAT"]) && $arResult["USERS"]["FORMAT"] == 'EVENING'){
				?>
            <p class="accept_users"><a href="/admin/service/excel.php?excel=user_evening" style="margin-left:10px;"><input name="excel" type="text" value="������������ Excel" style="cursor:pointer;" /></a>  <a href="/admin/service/excel.php?excel=user_evening_all" style="margin-left:10px;"><input name="excel" type="text" value="Excel (��� ����)" style="cursor:pointer;" /></a></p>
				<?
			}
			elseif(isset($arResult["USERS"]["FORMAT"]) && $arResult["USERS"]["FORMAT"] == 'HB'){
				?>
            <p class="accept_users"><a href="/admin/service/excel.php?excel=user_hb" style="margin-left:10px;"><input name="excel" type="text" value="������������ Excel" style="cursor:pointer;" /></a>  <a href="/admin/service/excel.php?excel=user_hb_all" style="margin-left:10px;"><input name="excel" type="text" value="Excel (��� ����)" style="cursor:pointer;" /></a></p>
				<?
			}
			else{
				?>
            <p class="accept_users"><a href="/admin/service/excel.php?excel=user_morning" style="margin-left:10px;"><input name="excel" type="text" value="������������ Excel" style="cursor:pointer;" /></a>  <a href="/admin/service/excel.php?excel=user_morning_all" style="margin-left:10px;"><input name="excel" type="text" value="Excel (��� ����)" style="cursor:pointer;" /></a></p>
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
				elseif(strpos($arResult["FIELDS"][$i]["TITLE"], "(�����)") === false && $arResult["FIELDS"][$i]["TITLE"] != "�������������� e-mail"){
			  	?>
                <td width="100"><strong><?=$arResult["FIELDS"][$i]["TITLE"]?></strong></td>
				<?
				}
			  }
			  ?>
                <td width="80"><strong>�������������</strong></td>
                <td width="80"><strong>����������</strong></td>
                <td width="80"><strong>Wish-����</strong></td>
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
				elseif(strpos($arResult["FIELDS"][$i]["TITLE"], "(�����)") === false && $arResult["FIELDS"][$i]["TITLE"] != "�������������� e-mail"){
					$arResult["USERS"][$j]["FIELDS"][$i]=str_replace("�������� ������ (��� �������������� ����������� ������)","�������� ������",$arResult["USERS"][$j]["FIELDS"][$i]);
			  	?>
                <td><?=$arResult["USERS"][$j]["FIELDS"][$i]?></td>
				<?
				}
			  }
			  ?>
                <td width="80"><a href="/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit" target="_blank" onclick="newWind('/admin/service/edit.php?id=<?=$arResult["USERS"][$j]["ID"]?>&result=<?=$arResult["USERS"][$j]["ANKETA"]?>&type=edit', 500, 600); return false;">�������������</a></td>
                <td width="80"><a href="/admin/service/shedule_guest.php?id=<?=$arResult["USERS"][$j]["ID"]?>" target="_blank" onclick="newWind('/admin/service/shedule_guest.php?id=<?=$arResult["USERS"][$j]["ID"]?>', 650, 600); return false;">������������</a></td>
                <td width="80"><a href="/admin/service/wish_guest.php?id=<?=$arResult["USERS"][$j]["ID"]?>" target="_blank" onclick="newWind('/admin/service/wish_guest.php?id=<?=$arResult["USERS"][$j]["ID"]?>', 650, 600); return false;">������������</a></td>
                <td width="80"><a href="/admin/guest/off/?id=<?=$arResult["USERS"][$j]["ID"]?>&type=spam">����</a></td>
              </tr>
              <?
			  }
			  ?>
            </table>
            </form>
            <?
			}
			?>
			<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
            </div>
	<?

	//echo "<pre>"; print_r($arResult); echo "</pre>";
}
?>