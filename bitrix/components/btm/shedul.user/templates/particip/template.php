<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	if($arResult["MESSAGE"] != ''){
		?><p style="text-align:center;"><strong style="color:#F00;"><?=$arResult["MESSAGE"]?></strong></p><?
	}
	?>
		<script type="text/javascript">
		function newRequest(reciver, timeC){
			var timeList = document.getElementById('companys_' + reciver);
			var timeChoose = 0;
			optionIndex = timeList.selectedIndex;
			timeChoose = timeList.options[optionIndex].value;
			var recHref = '/personal/service/appointment.php?id='+timeChoose+'&time='+timeC;
        	window.open(recHref,'particip_appoint', 'scrollbars=yes,resizable=yes,width=400, height=200, left='+(screen.availWidth/2-200)+', top='+(screen.availHeight/2-100)+'');
			return false;
		}
		function newWish(reciver){
			var timeList = document.getElementById('companys_out');
			var timeChoose = 1;
			optionIndex = timeList.selectedIndex;
			timeChoose = timeList.options[optionIndex].value;
			var recHref = '/personal/service/wish.php?id='+timeChoose+'&wish=welcom';
        	window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width=400, height=200, left='+(screen.availWidth/2-200)+', top='+(screen.availHeight/2-100)+'');
			return false;
		}
		function newWind(reciver){
			var recHref = reciver;
        	window.open(recHref,'particip_appoint', 'scrollbars=yes,resizable=yes,width=400, height=200, left='+(screen.availWidth/2-200)+', top='+(screen.availHeight/2-100)+'');
			return false;
		}
		function newMessWind(reciver){
			var recHref = reciver;
        	window.open(recHref,'particip_appoint', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
			return false;
		}
        </script>
      <p class="reg_update"><a href="/personal/service/shedule_pdf.php" target="_blank">Generate schedule PDF</a></p>
      <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
          <tr class="chet">
              <td width="100"><strong>Time</strong></td>
              <td width="260"><strong>Company</strong></td>
              <td width="175"><strong>Representative</strong></td>
              <td width="95"><strong>Status</strong></td>
              <td colspan="2"><strong>Notes</strong></td>
          </tr>
          <?
		  for($i=1; $i<$arResult["APP_COUNT"]+1; $i++){
		    $counter = $i-1;
			  ?>
          <?
            if($arResult["SHEDULE"][$i]["NOTES"] == 'FREE'){
				?>
          <tr <? if($counter % 2){?>class="chet"<? }?>>
              <td><?=$arResult["SHEDULE"][$i]['TITLE']?></td>
              <td colspan="2">
              <select name="companys" style="width:430px;" id="companys_<?=$counter?>">
                  <option value="0">Choose a company</option>
                  <? 
                  foreach($arResult["SHEDULE"][$i]["LIST"]["COMPANYS"] as $key => $value){
                      echo "<option value='".$value["ID"]."'>".$value["NAME"]."</option>"."\n";
                  }
                  ?>
              </select>
              </td>
              <?
			  if($arParams["IS_ACTIVE"] == 'N'){
				  ?>
              <td>Blocked</td>
				  <?
			  }
			  else{
				  ?>
              <td><a href="/personal/service/appointment.php?id=1&time='<?=$counter?>'" target="_blank" onclick="newRequest('<?=$counter?>', '<?=$counter?>'); return false;">Send a request</a></td>
				  <?
			  }
              ?>
              <td colspan="2">Free</td>
          </tr>
				<?
			}
			elseif($arResult["SHEDULE"][$i]["NOTES"] == 'ACT'){
				?>
          <tr class="accept">
              <td><?=$arResult["SHEDULE"][$i]['TITLE']?></td>
              <td><?=$arResult["SHEDULE"][$i]["COMPANY"]?></td>
              <td><?=$arResult["SHEDULE"][$i]["REP"]?></td>
              <td>Confirmed</td>
              <td><? if($arResult["SHEDULE"][$i]["STATUS"] == 'ADM'){?>Appointed by administrator<? } else{?>Accepted<? }?></td>
              <td><a href="/personal/service/write.php?id=<?=$arResult["SHEDULE"][$i]["PARTNER_ID"]?>" target="_blank" onclick="newMessWind('/personal/service/write.php?id=<?=$arResult["SHEDULE"][$i]["PARTNER_ID"]?>'); return false;">Send a message</a></td>
          </tr>
				<?
			}		  
			else{
				?>
          <tr <? if($counter % 2){?>class="chet"<? }?>>
              <td><?=$arResult["SHEDULE"][$i]['TITLE']?></td>
              <td><?=$arResult["SHEDULE"][$i]["COMPANY"]?></td>
              <td><?=$arResult["SHEDULE"][$i]["REP"]?></td>
              <?
              if($arResult["SHEDULE"][$i]["STATUS"] == 'MY'){
				  ?>
              <td><a href="/personal/service/appointment_edit.php?meetact=decline&meet_id=<?=$arResult["SHEDULE"][$i]['ID']?>" target="_blank" onclick="newWind('/personal/service/appointment_edit.php?meetact=decline&meet_id=<?=$arResult["SHEDULE"][$i]['ID']?>'); return false;">Cancel</a></td>
              <td>Request sent</td>
              <td><a href="/personal/service/write.php?id=<?=$arResult["SHEDULE"][$i]["PARTNER_ID"]?>" target="_blank" onclick="newMessWind('/personal/service/write.php?id=<?=$arResult["SHEDULE"][$i]["PARTNER_ID"]?>'); return false;">Send a message</a></td>
				  <?
			  }
			  elseif($arResult["SHEDULE"][$i]["STATUS"] == 'ADM'){
				  ?>
              <td><a href="/personal/service/appointment_edit.php?meetact=accept&meet_id=<?=$arResult["SHEDULE"][$i]['ID']?>" target="_blank" onclick="newWind('/personal/service/appointment_edit.php?meetact=accept&meet_id=<?=$arResult["SHEDULE"][$i]['ID']?>'); return false;">Accept</a><br />
              <a href="/personal/service/appointment_edit.php?meetact=decline&meet_id=<?=$arResult["SHEDULE"][$i]['ID']?>" target="_blank" onclick="newWind('/personal/service/appointment_edit.php?meetact=decline&meet_id=<?=$arResult["SHEDULE"][$i]['ID']?>'); return false;">Decline</a></td>
              <td>Appointed by administrator</td>
              <td><a href="/personal/service/write.php?id=<?=$arResult["SHEDULE"][$i]["PARTNER_ID"]?>" target="_blank" onclick="newMessWind('/personal/service/write.php?id=<?=$arResult["SHEDULE"][$i]["PARTNER_ID"]?>'); return false;">Send a message</a></td>
				  <?
			  }
			  else{
				  ?>
              <td><a href="/personal/service/appointment_edit.php?meetact=accept&meet_id=<?=$arResult["SHEDULE"][$i]['ID']?>" target="_blank" onclick="newWind('/personal/service/appointment_edit.php?meetact=accept&meet_id=<?=$arResult["SHEDULE"][$i]['ID']?>'); return false;">Accept</a><br />
              <a href="/personal/service/appointment_edit.php?meetact=decline&meet_id=<?=$arResult["SHEDULE"][$i]['ID']?>" target="_blank" onclick="newWind('/personal/service/appointment_edit.php?meetact=decline&meet_id=<?=$arResult["SHEDULE"][$i]['ID']?>'); return false;">Decline</a></td>
              <td>Request received</td>
              <td><a href="/personal/service/write.php?id=<?=$arResult["SHEDULE"][$i]["PARTNER_ID"]?>" target="_blank" onclick="newMessWind('/personal/service/write.php?id=<?=$arResult["SHEDULE"][$i]["PARTNER_ID"]?>'); return false;">Send a message</a></td>
				  <?
			  }
			  ?>
          </tr>
				<?
			}		  
		  ?>
			  <?
		  }
		  ?>
      </table>
      
      <p>&nbsp;</p>
      <p class="reg_update"><a href="/personal/service/wish_pdf.php" target="_blank">Generate PDF for wish list</a></p>
      <p>In this section you can request guests whose schedules have already been filled.</p>
      <table width="100%" border="0">
        <tr>
          <td width="350">
              <p><strong>My wish list for appointments outside of the morning session</strong></p>
          </td>
          <td>
          <p><strong>The following guests are interested in an appointment with you outside of the morning session</strong></p>
          </td>
        </tr>
        <tr>
          <td valign="top" width="350">
              <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
                  <tr class="chet">
                      <td width="20"><strong>№</strong></td>
                      <td><strong>Company</strong></td>
                  </tr>
                  <?
				  $counter = 0;
				  foreach($arResult["WISH_OUT"] as $key => $value){
					  ?>
                  <tr <? if($counter % 2){?>class="chet"<? }?>>
                      <td><?=$counter+1?></td>
                      <td><?=$value?></td>
                  </tr>
					  <?
					  $counter++;
				  }
				  ?>
              </table>
              <p><strong><a href="/personal/service/wish.php?id=0&wish=welcom" target="_blank" onclick="newWish('out'); return false;">Send a request</a></strong></p>
              <select name="companys_out" style="width:250px;" id="companys_out" class="companys_out">
                  <option value="0">Choose a company</option>
                  <?
				  foreach($arResult["NOT_FREE"] as $key => $value){
					  echo "<option value='".$key."'>".$value."</option>"."\n";
				  }
				  ?>
              </select>
          </td>
          <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
              <tr class="chet">
                  <td width="20"><strong>№</strong></td>
                  <td width="190"><strong>Company</strong></td>
                  <td><strong>&nbsp;</strong></td>
              </tr>
                  <?
				  $counter = 0;
				  foreach($arResult["WISH_IN"] as $key => $value){
					  ?>
                  <tr <? if($counter % 2){?>class="chet"<? }?>>
                      <td><?=$counter+1?></td>
                      <td><?=$value?></td>
                      <td><a href="/personal/service/write.php?id=<?=$key?>" target="_blank" onclick="newMessWind('/personal/service/write.php?id=<?=$key?>'); return false;">Send a message</a></td>

                  </tr>
					  <?
					  $counter++;
				  }
				  ?>
            </table>
          </td>
        </tr>
      </table>
      
	<?
	//echo "<pre>"; print_r($arResult); echo "</pre>";
}
else{
	?>
    <br />
    <br />
    <p style="padding-left:10px;"><?=$arResult["ERROR_MESSAGE"]?></p>
    <br />
    <br />
	<?
}
?>