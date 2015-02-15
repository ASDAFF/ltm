<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
    <script type="text/javascript" src="/bitrix/components/btm/user.personal.list/templates/.default/script.js"></script>
        <table width="100%" border="0" cellspacing="5" cellpadding="0">
          <tr>
            <td><p class="reg_update" style="text-align:left;"><? if($arResult["FILTERS"]["MAIN"][0]["ACTIVE"] == Y){?><strong style="color:#FF7900;"><?=$arResult["FILTERS"]["MAIN"][0]["NAME"]?></strong><? } else{?><a href="<?=$arResult["FILTERS"]["MAIN"][0]["LINK"]?>" style=" color:#000;"><?=$arResult["FILTERS"]["MAIN"][0]["NAME"]?></a><? }?></p></td>
            <td><p class="reg_update" style="text-align:center;"><? if($arResult["FILTERS"]["MAIN"][1]["ACTIVE"] == Y){?><strong style="color:#FF7900;"><?=$arResult["FILTERS"]["MAIN"][1]["NAME"]?></strong><? } else{?><a href="<?=$arResult["FILTERS"]["MAIN"][1]["LINK"]?>" style=" color:#000;"><?=$arResult["FILTERS"]["MAIN"][1]["NAME"]?></a><? }?></p></td>
            <td><p class="reg_update"><? if($arResult["FILTERS"]["MAIN"][2]["ACTIVE"] == Y){?><strong style="color:#FF7900;"><?=$arResult["FILTERS"]["MAIN"][2]["NAME"]?></strong><? } else{?><a href="<?=$arResult["FILTERS"]["MAIN"][2]["LINK"]?>" style=" color:#000;"><?=$arResult["FILTERS"]["MAIN"][2]["NAME"]?></a><? }?></p></td>
            <td><p class="reg_update"><? if($arResult["FILTERS"]["MAIN"][3]["ACTIVE"] == Y){?><strong style="color:#FF7900;"><?=$arResult["FILTERS"]["MAIN"][3]["NAME"]?></strong><? } else{?><a href="<?=$arResult["FILTERS"]["MAIN"][3]["LINK"]?>" style=" color:#000;"><?=$arResult["FILTERS"]["MAIN"][3]["NAME"]?></a><? }?></p></td>
            <td width="70"><p class="reg_update"><? if($arResult["FILTERS"]["MAIN"][4]["ACTIVE"] == Y){?><strong style="color:#FF7900;"><?=$arResult["FILTERS"]["MAIN"][4]["NAME"]?></strong><? } else{?><a href="<?=$arResult["FILTERS"]["MAIN"][4]["LINK"]?>" style=" color:#000;"><?=$arResult["FILTERS"]["MAIN"][4]["NAME"]?></a><? }?></p></td>
          </tr>
        </table>
        <?=$arResult["FILTERS"]["SUB"]?>
        <?
		if($arResult["USERS"]["COUNT"] == 0){
			?>
            <p>&nbsp;</p>
            <p><strong style="color:#FF7900;">There are no companies for this meaning.</strong></p>
			<?
		}
		else{
		?>
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
            <tr class="chet">
                <td><strong>Company</strong></td>
                <td width="130"><strong>Representative</strong></td>
                <td width="75"><strong>Write</strong></td>
                <td width="105"><strong>Free time</strong></td>
                <td width="80"><strong>Request</strong></td>
            </tr>
              <?
              for($j=0; $j<$arResult["USERS"]["COUNT"]; $j++){
			  $countPhone = 0;
			  $countName = 0;
			  ?>
              <tr <? if(($j % 2)){?>class="chet"<? }?>>
                  <td>
                      <strong><?=$arResult["USERS"][$j]["FIELDS"][2]?></strong><br />
                      <?=$arResult["USERS"][$j]["FIELDS"][22]?><br />
                      <a href="#" onclick="TopMenuOver('cat<?=$arResult["USERS"][$j]["ID"]?>'); return false;" id="cat<?=$arResult["USERS"][$j]["ID"]?>par">More</a>
                  </td>
                  <td><?=$arResult["USERS"][$j]["FIELDS"][0]?> <?=$arResult["USERS"][$j]["FIELDS"][1]?></td>
                  <td>Send a message</td>
                  <td>
                  <select name="times" style="width:90px;" id="list_<?=$arResult["USERS"][$j]["ID"]?>">
                      <option value='1'>10.00 - 10.15</option>
                      <option value='2'>10:20 – 10:35</option>
                  </select>
                  </td>
                  <td>Send a request</td>
                </tr>
                <tr <? if(($j % 2)){?>class="chet"<? }?>>
                  <td colspan="5" height="1" style="padding:0 5px 0;">
                  	<p class="descr" id="cat<?=$arResult["USERS"][$j]["ID"]?>" style="display:none;">
                          <strong>Site</strong>: <a href="http://<?=$arResult["USERS"][$j]["FIELDS"][13]?>"><?=$arResult["USERS"][$j]["FIELDS"][13]?></a><br />
                          <strong>Address</strong>: <?=$arResult["USERS"][$j]["FIELDS"][5]?>, <?=$arResult["USERS"][$j]["FIELDS"][4]?><br />
                          <?=$arResult["USERS"][$j]["FIELDS"][21]?><br />
                    </p>
                  </td>
                </tr>
              <?
			  }
			  ?>
            </table>
	<?
	}
	//echo "<pre>"; print_r($arResult["USERS"]); echo "</pre>";
}
?>