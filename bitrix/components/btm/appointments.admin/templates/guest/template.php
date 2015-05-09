<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
	<script type="text/javascript">
    function newRequest(reciver, timeC){
        var timeList = document.getElementById('companys_' + reciver + "time" + timeC);
        var timeChoose = 1;
        optionIndex = timeList.selectedIndex;
        timeChoose = timeList.options[optionIndex].value;
        var recHref = '/admin/service/appointment_guest.php?id='+reciver+'&to='+timeChoose+'&time='+timeC+'&meet=accept';
        window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
        return false;
    }
    function declineRequest(reciver, meetact){
        var recHref = '/admin/service/appointment_edit.php?meetact='+meetact+'&meet_id='+reciver;
        window.open(recHref,'appointment', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
        return false;
    }
    </script>
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
    <table border="0" cellspacing="0" cellpadding="10" class="admin_info">
      <tr>
        <td class="active"><strong>Подтвержденная встреча</strong></td>
        <td class="particip"><strong style="color:#000;">Встреча назначенная<br />участником</strong></td>
        <td class="guest"><strong style="color:#FFF;">Встреча назначенная<br />гостем</strong></td>
      </tr>
    </table><br />
    <table border="0" cellspacing="0" cellpadding="3" class="admin_info">
        <tr class="chet">
            <td width="120"><strong>Компания и Представитель</strong></td>
    <?
    for ($i = 0; $i < $arResult["TIMES_COUNT"]; $i++) {
        ?><td width="110"><strong><?=$arResult["TIMES"][$i]?></strong></td><?
    }
    ?>
        </tr>
    <?
	for ($j = 0; $j < $arResult["USERS"]["COUNT"]; $j++) {
		?>
        <tr>
            <td width="120">
				<?=$arResult["USERS"]["LIST"][$j]["COMPANY"]?><br />
                <?=$arResult["USERS"]["LIST"][$j]["REP"]?>
            </td>
	  <?
      for ($i = 0; $i < $arResult["TIMES_COUNT"]; $i++) {
		 // print_r($arResult["USERS"][$j]["MEET"][$i]);
		  if($arResult["USERS"]["LIST"][$j]["MEET"][$i]["STATUS"] == "FREE"){
			  ?>
            <td>
              <select name="companys_out" style="width:80px;" id="companys_<?=$arResult["USERS"]["LIST"][$j]["ID"]?>time<?=$i?>" class="companys_out">
                  <option value="0">Выберите компанию</option>
                  <? 
                  foreach($arResult["USERS"]["LIST"][$j]["MEET"][$i]["LIST"] as $key => $value){
                      echo "<option value='".$value["ID"]."'>".$value["COMPANY"]."</option>"."\n";
                  }
                  ?>
              </select><br />
              <a href="/admin/service/appointment_guest.php?id=<?=$arResult["USERS"]["LIST"][$j]["ID"]?>&to=1&time=<?=$i?>&meet=accept" target="_blank" onclick="newRequest('<?=$arResult["USERS"]["LIST"][$j]["ID"]?>','<?=$i?>'); return false;">Назначить</a>
			  <?
		  }
		  elseif($arResult["USERS"]["LIST"][$j]["MEET"][$i]["ACTIVE"] == 'Y'){
			  ?>
	        <td class="active">
            	<strong><?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["COMPANY"]?></strong><br />
                <strong><?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["REP"]?></strong><br />
                <a href="/admin/service/appointment_edit.php?meetact=decline&meet_id=<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>" target="_blank" onclick="declineRequest('<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>', 'decline'); return false;">Отменить</a>
			  <?
		  }
		  elseif($arResult["USERS"]["LIST"][$j]["MEET"][$i]["STATUS"] == 'FROM'){
			  ?>
	        <td class="guest">
            	<strong><?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["COMPANY"]?></strong><br />
                <strong><?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["REP"]?></strong><br />
                <a href="/admin/service/appointment_edit.php?meetact=decline&meet_id=<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>" target="_blank" onclick="declineRequest('<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>', 'decline'); return false;">Отменить</a><br />
                <a href="/admin/service/appointment_edit.php?meetact=accept&meet_id=<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>" target="_blank" onclick="declineRequest('<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>', 'accept'); return false;">Подтвердить</a>
			  <?
		  }
		  elseif($arResult["USERS"]["LIST"][$j]["MEET"][$i]["STATUS"] == 'TO'){
			  ?>
	        <td class="particip">
            	<strong><?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["COMPANY"]?></strong><br />
                <strong><?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["REP"]?></strong><br />
                <a href="/admin/service/appointment_edit.php?meetact=decline&meet_id=<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>" target="_blank" onclick="declineRequest('<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>', 'decline'); return false;">Отменить</a><br />
                <a href="/admin/service/appointment_edit.php?meetact=accept&meet_id=<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>" target="_blank" onclick="declineRequest('<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>', 'accept'); return false;">Подтвердить</a>
			  <?
		  }
		  else{
			  ?>
	        <td>
            	<strong><?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["COMPANY"]?></strong><br />
                <strong><?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["REP"]?></strong><br />
                <a href="/admin/service/appointment_edit.php?meetact=decline&meet_id=<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>" target="_blank" onclick="declineRequest('<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>', 'decline'); return false;">Отменить</a><br />
                <a href="/admin/service/appointment_edit.php?meetact=accept&meet_id=<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>" target="_blank" onclick="declineRequest('<?=$arResult["USERS"]["LIST"][$j]["MEET"][$i]["ID"]?>', 'accept'); return false;">Подтвердить</a>
			  <?
		  }
        ?>
        </td>
		<?
	  }
	  ?>
        </tr>
		<?
	}
	?>
    </table>
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
	<?
	//echo "<pre>"; print_r($arResult["USERS"]["LIST"]); echo "</pre>";
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