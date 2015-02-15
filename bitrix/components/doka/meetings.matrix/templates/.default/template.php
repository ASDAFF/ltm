<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->AddHeadString('<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>',true);
?>
<table border="0" cellspacing="0" cellpadding="10" id="legenda">
  <tr>
    <td class = "confirmed"><strong>Подтвержденная встреча</strong></td>
    <td class="yellow"><strong style="color:#000;">Встреча назначенная<br />участником</strong></td>
    <td class="red"><strong style="color:#FFF;">Встреча назначенная<br />гостем</strong></td>
  </tr>
</table><br />
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
    <div class="timeslots-free">
        <?foreach($arResult['TIMES_FREE'] as $timesId => $timesList):?>
        <div id="time-list<?=$timesId?>">
            <?if($timesList == ''):?>
                Все таймслоты заняты
            <?else:?>
                <select>
                    <?=$timesList?>
                </select>
            <?endif;?>
        </div>
        <?endforeach;?>
    </div>
<?
switch ($arResult['USER_TYPE']) {
	case 'PARTICIP':
		include_once(dirname(__FILE__) . '/particip.php');
		break;
	case 'GUEST':
		include_once(dirname(__FILE__) . '/guest.php');
}
?>
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
<script>
	$(document).ready(function() {
    	// $('#results').fixedHeaderTable({ 
    	// 	footer: false, 
    	// 	cloneHeadToFoot: true, 
    	// 	altClass: 'odd', 
    	// 	autoShow: true, 
    	// 	fixedColumns: 1, 
    	// 	height: 400
    	// });
	});
</script>
