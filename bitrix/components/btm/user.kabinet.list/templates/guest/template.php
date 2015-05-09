<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
    <script type="text/javascript">
	  $(document).ready(function(){
		$("a.more").click(function () {
			$("#"+$(this).attr("more")).toggle();
			return false;
		})
	  }); 	
		function newRequest(reciver){
			var timeList = document.getElementById('list_' + reciver);
			var timeChoose = 1;
			optionIndex = timeList.selectedIndex;
			timeChoose = timeList.options[optionIndex].value;
			var recHref = '/ru/personal/service/appointment.php?id='+reciver+'&time='+timeChoose;
        	window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
			return false;
		}
		function newWind(reciver){
			var recHref = reciver;
        	window.open(recHref,'particip_appoint', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
			return false;
		}
    
    </script>
    <table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td><p class="reg_update" style="text-align:left;"><? if($arResult["SORT"] == 'ABC'){?><strong style="color:#66CCFF;">В алфавитном порядке</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=abc" style=" color:#FFF;">В алфавитном порядке</a><? }?></p></td>
        <td><p class="reg_update" style="text-align:center;"><? if($arResult["SORT"] == "COUNTRIES"){?><strong style="color:#66CCFF;">По странам</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=country" style=" color:#FFF;">По странам</a><? }?></p></td>
        <td><p class="reg_update"><? if($arResult["SORT"] == "BUSINESS"){?><strong style="color:#66CCFF;">По виду деятельности</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=business" style=" color:#FFF;">По виду деятельности</a><? }?></p></td>
        <td><p class="reg_update"><? if($arResult["SORT"] == "TIMES"){?><strong style="color:#66CCFF;">По свободному времени</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=times" style=" color:#FFF;">По свободному времени</a><? }?></p></td>
        <td width="70"><p class="reg_update"><? if($arResult["SORT"] == "ALL"){?><strong style="color:#66CCFF;">Все</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=all" style=" color:#FFF;">Все</a><? }?></p></td>
      </tr>
    </table>
    <p class="filter_block"><?=$arResult["FILTER"]["SUB"]?></p>
    <div class="filter_block"><?=$arResult["NAVIGATE"]?></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
        <tr class="chet">
            <td><strong>Компания</strong></td>
            <td width="130"><strong>Представитель</strong></td>
            <td width="75"><strong>Написать</strong></td>
            <td width="105"><strong>Свободное время</strong></td>
            <td width="80"><strong>Запрос</strong></td>
        </tr>
	<?
    $countUsers = 0;
    for($j=0; $j<$arResult["COUNT"]; $j++){
        ?>
        <tr <? if($countUsers % 2){?>class="chet"<? }?>>
            <td>
            <strong><?=$arResult["USERS"][$j]["FIELDS"]["COMPANY"]?></strong><br />
            <?=$arResult["USERS"][$j]["FIELDS"]["BUSINESS"]?><br /><br />        
            <?=$arResult["USERS"][$j]["FIELDS"]["COUNTRY_LIST"]?><br />
            <a href="#" more="user_<?=$arResult["USERS"][$j]["ID"]?>" class="more">Подробнее</a>
            </td>
            <td width="130"><?=$arResult["USERS"][$j]["FIELDS"]["NAME"]?></td>
            <td width="75"><a href="/ru/personal/service/write.php?id=<?=$arResult["USERS"][$j]["ID"]?>" target="_blank" onclick="newWind('/ru/personal/service/write.php?id=<?=$arResult["USERS"][$j]["ID"]?>'); return false;">Написать сообщение</a></td>
            <? if($arResult["USERS"][$j]["COUNT_APP"] > 0){
				?>
            <td width="105">
              <select name="times" style="width:90px;" id="list_<?=$arResult["USERS"][$j]["ID"]?>">
                <? 
				for($i=0; $i < $arResult["TIMES"]["COUNT"]; $i++){
					if($arResult["USERS"][$j]["APPOINTMENTS"][$i] == ''){
						echo "<option value='".$i."'>".$arResult["TIMES"]["VALUES"][$i]."</option>"."\n";
					}
				}
                ?>
            </select>
            </td>
            <td width="80"><a href="/ru/personal/service/appointment.php?id=<?=$arResult["USERS"][$j]["ID"]?>&time=0" target="_blank" onclick="newRequest('<?=$arResult["USERS"][$j]["ID"]?>'); return false;">Послать запрос</a></td>
				<?
				}
			else{
				?>
            <td colspan="2">
            	Расписание полное
            </td>
				<?
			}
			?>
        </tr>
        <tr <? if($countUsers % 2){?>class="chet"<? }?> style="display:none;" id="user_<?=$arResult["USERS"][$j]["ID"]?>">
            <td colspan="5" height="1">
            <strong>Сайт</strong>: <a href="http://<?=$arResult["USERS"][$j]["FIELDS"]["SITE"]?>" target="_blank"><?=$arResult["USERS"][$j]["FIELDS"]["SITE"]?></a><br />
            <strong>Адрес</strong>: <?=$arResult["USERS"][$j]["FIELDS"]["CITY"]?>, <?=$arResult["USERS"][$j]["FIELDS"]["ADRESS"]?><br />
            <?=nl2br($arResult["USERS"][$j]["FIELDS"]["DESC"])?>
            </td>
        </tr>
        
        <?
		$countUsers++;
    }
    ?>
    </table>
    <div class="filter_block"><?=$arResult["NAVIGATE"]?></div>
  <?
}
//echo "<pre>"; print_r($arResult["USERS"]); echo "</pre>";
?>