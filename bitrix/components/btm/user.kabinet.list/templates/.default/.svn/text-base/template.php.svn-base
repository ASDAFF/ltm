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
			var recHref = '/personal/service/appointment.php?id='+reciver+'&time='+timeChoose;
        	window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width=400, height=200, left='+(screen.availWidth/2-200)+', top='+(screen.availHeight/2-100)+'');
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
        <td><p class="reg_update" style="text-align:left;"><? if($arResult["SORT"] == 'ABC'){?><strong style="color:#66CCFF;">In alphabetical order</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=abc" style=" color:#FFF;">In alphabetical order</a><? }?></p></td>
        <td><p class="reg_update" style="text-align:center;"><? if($arResult["SORT"] == "COUNTRIES"){?><strong style="color:#66CCFF;">By country of interest</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=country" style=" color:#FFF;">By country of interest</a><? }?></p></td>
        <td><p class="reg_update"><? if($arResult["SORT"] == "CITY"){?><strong style="color:#66CCFF;">By city of origin</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=city" style=" color:#FFF;">By city of origin</a><? }?></p></td>
        <td><p class="reg_update"><? if($arResult["SORT"] == "TIMES"){?><strong style="color:#66CCFF;">By available slots</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=times" style=" color:#FFF;">By available slots</a><? }?></p></td>
        <td width="70"><p class="reg_update"><? if($arResult["SORT"] == "ALL"){?><strong style="color:#66CCFF;">All</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=all" style=" color:#FFF;">All</a><? }?></p></td>
      </tr>
    </table>
    <p class="filter_block"><?=$arResult["FILTER"]["SUB"]?></p>
    <div class="filter_block"><?=$arResult["NAVIGATE"]?></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
        <tr class="chet">
            <td><strong>Company</strong></td>
            <td width="130"><strong>Representative</strong></td>
            <td width="75"><strong>Contact</strong></td>
            <td width="105"><strong>Free slots</strong></td>
            <td width="80"><strong>Request</strong></td>
        </tr>
	<?
    $countUsers = 0;
    for($j=0; $j<$arResult["COUNT"]; $j++){
        ?>
        <tr <? if($countUsers % 2){?>class="chet"<? }?>>
            <td>
            <strong><?=$arResult["USERS"][$j]["FIELDS"]["COMPANY"]?></strong><br />
            <?=implode(', ',$arResult["USERS"][$j]["FIELDS"]["COUNTRY"])?><br />
            <a href="#" more="user_<?=$arResult["USERS"][$j]["ID"]?>" class="more">More</a>
            </td>
            <td width="130"><?=$arResult["USERS"][$j]["FIELDS"]["NAME"]?></td>
            <td width="75"><a href="/personal/service/write.php?id=<?=$arResult["USERS"][$j]["ID"]?>" target="_blank" onclick="newWind('/personal/service/write.php?id=<?=$arResult["USERS"][$j]["ID"]?>'); return false;">Send a message</a></td>
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
            <td width="80"><a href="/personal/service/appointment.php?id=<?=$arResult["USERS"][$j]["ID"]?>&time=0" target="_blank" onclick="newRequest('<?=$arResult["USERS"][$j]["ID"]?>'); return false;">Send a request</a></td>
				<?
				}
			else{
				?>
            <td colspan="2">
            	The schedule is full
            </td>
				<?
			}
			?>
        </tr>
        <tr <? if($countUsers % 2){?>class="chet"<? }?> style="display:none;" id="user_<?=$arResult["USERS"][$j]["ID"]?>">
            <td colspan="5" height="1">
            <strong>Site</strong>: <a href="http://<?=$arResult["USERS"][$j]["FIELDS"]["SITE"]?>" target="_blank"><?=$arResult["USERS"][$j]["FIELDS"]["SITE"]?></a><br />
            <strong>Address</strong>: <?=$arResult["USERS"][$j]["FIELDS"]["CITY"]?>, <?=$arResult["USERS"][$j]["FIELDS"]["ADRESS"]?><br />
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