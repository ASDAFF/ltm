<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Luxury Travel Mart 2010");
$APPLICATION->SetTitle("Luxury Travel Mart 2010");?>
<?
if(!$USER->IsAuthorized()){
	header("Location:/personal/login.php");
}
else
{
	// получим массив групп текущего пользователя
	CModule::IncludeModule('iblock');
	$UID = $USER->GetID();
	global $USER;
	$arGroups = CUser::GetUserGroup($UID);;
	/* Администраторы */
	if (in_array(1, $arGroups)){
		//$UID = 2195;
		$rsUser = CUser::GetByID($UID);
		$arUser = $rsUser->Fetch();
		$welcome = $arUser['UF_PERSONAL_SALUT']." ".$arUser["NAME"]." ".$arUser["LAST_NAME"];
		$arFilter = Array(
		   "IBLOCK_ID" => 9,
		   "ACTIVE" => "N",
		   "PROPERTY_RECIEVER_ID" => $UID
		   );
		$resNum = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, array());
		$meetNum = 0;
		$meetNum = $meetNum + $arUser["WORK_MAILBOX"];
		?>
		<script>
        function toggle_desc(id)
        {
          if (document.getElementById('desc_' + id).style.display == 'none') document.getElementById('desc_' + id).style.display = 'block';
          else document.getElementById('desc_' + id).style.display = 'none';
        }
        </script>
            <div class="hello_div">
                <div class="welcome">
                    <p><strong>Добро пожаловать, <?=$welcome?></strong></p>
                    <p>У Вас <span><?=$meetNum?></span> неподтвержденных запросов на встречи и <span><?=$resNum?></span> новых сообщений</p>
                </div>
                <div class="logout"><a href="/personal/logout.php"><img src="/local/templates/personal/images/logout.gif" width="63" height="21" alt="Log Out" border="0" /></a></div>
            </div>
        	<div class="menu_personal">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="text-align:left; width:180px;"><a href="/personal/shedule/admin/admin.php"><img src="/personal/shedule/images/particip.gif" width="142" height="31" border="0" /></a></td>
                <td style="text-align:left; width:180px;"><a href="/personal/shedule/admin/admin_guest.php"><img src="/personal/shedule/images/guest.gif" width="142" height="31" border="0" /></a></td>
                <td style="text-align:left; width:180px;"><a href="/personal/shedule/admin/archiv_guest.php"><img src="/personal/shedule/images/archiv_guest.gif" width="142" height="31" border="0" /></a></td>
                <td style="text-align:left; width:180px;"><a href="/personal/shedule/admin/archiv_partcip.php"><img src="/personal/shedule/images/archiv_particip.gif" width="142" height="31" border="0" /></a></td>
              </tr>
              <tr>
                <td style="text-align:left; width:180px;"><a href="/personal/shedule/admin/message.php"><img src="/local/templates/personal/images/ru_message_act.gif" width="142" height="31" border="0" /></a></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="text-align:right;">&nbsp;</td>
              </tr>
            </table>
            </div>
            <div class="content_area">
            	<div class="content">
                	<table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="190" valign="top" height="30"><img src="/personal/message/images/message_title.gif" border="0" /></td>
                        <?
                        	if($resNum){
								echo '<td valign="top"><img src="/personal/message/images/envelope.gif" border="0" /></td>';
							}
						?>
                      </tr>
                    </table>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                      <tr>
                        <td style="text-align:left; width:200px;"><a href="/personal/shedule/admin/message.php"><img src="/personal/message/images/ru_mes_recive.gif" width="144" height="33" border="0" /></a></td>
                        <td style="text-align:left; width:200px;"><a href="/personal/shedule/admin/message_send.php"><img src="/personal/message/images/ru_mes_send.gif" width="144" height="33" border="0" /></a></td>
                        <td style="text-align:left; width:200px;"><a href="/personal/shedule/admin/message_write.php"><img src="/personal/message/images/ru_mes_write_act.gif" width="144" height="33" border="0" /></a></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <br /><br />
                <?
                if((isset($_POST['mes'])) and ($_POST['mes'] == 'write')){
					$message = '';
					if($_REQUEST["fio"]){
						$message = $message."ФИО: ".$_REQUEST["fio"];
						$message = $message."\n";
					}
					if($_REQUEST["company"]){
						$message = $message."Компания: ".$_REQUEST["company"];
						$message = $message."\n";
					}
					if($_REQUEST["email"]){
						$message = $message."E-mail: ".$_REQUEST["email"];
						$message = $message."\n";
					}
					if($_REQUEST["message_text"]){
						$message = $message."Текст сообщения: ".$_REQUEST["message_text"];
						$message = $message."\n";
					}
					if($message){
						$PROP = array();
						$PROP[5] = $UID;
						$PROP[7] = $_REQUEST["subj"];
						$PROP[8] = Array("VALUE" => Array ("TEXT" => $_REQUEST["message_text"], "TYPE" => "html или text"));
						$PROP[9] = ConvertTimeStamp(false, "FULL");
						$PROP[10] = 0;
						$PROP[12] = 'Администрация';
						if((isset($_POST['all_guest']) and isset($_POST['all_guest']) != '') or (isset($_POST['all_particip']) and isset($_POST['all_particip']) != '')){
							$error = 0;
							if(isset($_POST['all_guest']) and isset($_POST['all_guest']) != ''){
								$filter = Array
								(
									"GROUPS_ID"  => Array(16)
								);
								$rsGuests = CUser::GetList(($by="work_company"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // выбираем пользователей
								$is_filtered = $rsGuests->is_filtered; // отфильтрована ли выборка ?
								while($arParticip=$rsGuests->GetNext()) :
									$PROP[6] = $arParticip['ID'];
									$PROP[11] = $arParticip['NAME']." ".$arParticip['LAST_NAME']." Компания ".$arParticip['WORK_COMPANY'];
									$message = new CIBlockElement;
									$arLoadProductArray = Array(
									  "MODIFIED_BY"    => $UID, // элемент изменен текущим пользователем
									  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
									  "IBLOCK_ID"      => 9,
									  "PROPERTY_VALUES"=> $PROP,
									  "NAME"           => ConvertTimeStamp(false, "FULL")." От ".$UID." К ".$arParticip['ID'],
									  "ACTIVE"         => "N",            // активен
									  "PREVIEW_TEXT"   => substr($_REQUEST["message_text"],0,100)." ...",
									  "DETAIL_TEXT"    => $_REQUEST["message_text"]
									  );
									if(!($PRODUCT_ID = $message->Add($arLoadProductArray))){
									  echo "<p style='color:FF0000;'>Error: ".$message->LAST_ERROR."</p>";
										$error++;
									}
									else{
									  $arFieldsMes = array();
									  $arFieldsMes["EMAIL"] = $arParticip['EMAIL'];
									  CEvent::Send(
										  "ANEW_MESSAGE",
										  "ru",
										  $arFieldsMes
									  );
									}
								endwhile;
							}
							if(isset($_POST['all_particip']) and isset($_POST['all_particip']) != ''){
								$filter = Array
								(
									"GROUPS_ID"  => Array(4)
								);
								$rsParticip = CUser::GetList(($by="work_company"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // выбираем пользователей
								$is_filtered = $rsParticip->is_filtered; // отфильтрована ли выборка ?
								while($arParticip=$rsParticip->GetNext()) :
									$PROP[6] = $arParticip['ID'];
									$PROP[11] = $arParticip['NAME']." ".$arParticip['LAST_NAME']." Компания ".$arParticip['WORK_COMPANY'];
									$message = new CIBlockElement;
									$arLoadProductArray = Array(
									  "MODIFIED_BY"    => $UID, // элемент изменен текущим пользователем
									  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
									  "IBLOCK_ID"      => 9,
									  "PROPERTY_VALUES"=> $PROP,
									  "NAME"           => ConvertTimeStamp(false, "FULL")." От ".$UID." К ".$arParticip['ID'],
									  "ACTIVE"         => "N",            // активен
									  "PREVIEW_TEXT"   => substr($_REQUEST["message_text"],0,100)." ...",
									  "DETAIL_TEXT"    => $_REQUEST["message_text"]
									  );
									if(!($PRODUCT_ID = $message->Add($arLoadProductArray))){
									    echo "<p style='color:FF0000;'>Error: ".$message->LAST_ERROR."</p>";
										$error++;
									}
									else{
									  $arFieldsMes = array();
									  $arFieldsMes["EMAIL"] = $arParticip['EMAIL'];
									  CEvent::Send(
										  "ANEW_MESSAGE",
										  "ru",
										  $arFieldsMes
									  );
									}
								endwhile;
							}
							if(!$error){
								echo "Все ваши сообщения были успешно отправленны";
							}
						}
						else{
							$arGuestList = '';
							$arReciver = array();
							if(isset($_POST['guests'])){
								foreach($_POST['guests'] as $reciverIDS){
									$arGuestList .= " | ".$reciverIDS;
									$reciverID = "reciver_".$reciverIDS;
									$arReciver[$reciverIDS] = $_POST[$reciverID];
								}
							}
							if(isset($_POST['particip'])){
								foreach($_POST['particip'] as $reciverIDS){
									$arGuestList .= " | ".$reciverIDS;
									$reciverID = "reciver_".$reciverIDS;
									$arReciver[$reciverIDS] = $_POST[$reciverID];
								}
							}
							if($arGuestList){
								$error = 0;
								foreach($arReciver as $reciverProp => $reciver){
									$PROP[6] = $reciverProp;
									$PROP[11] = $reciver;
									$message = new CIBlockElement;
									$arLoadProductArray = Array(
									  "MODIFIED_BY"    => $UID, // элемент изменен текущим пользователем
									  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
									  "IBLOCK_ID"      => 9,
									  "PROPERTY_VALUES"=> $PROP,
									  "NAME"           => ConvertTimeStamp(false, "FULL")." От ".$UID." К ".$reciverProp,
									  "ACTIVE"         => "N",            // активен
									  "PREVIEW_TEXT"   => substr($_REQUEST["message_text"],0,100)." ...",
									  "DETAIL_TEXT"    => $_REQUEST["message_text"]
									  );
									if(!($PRODUCT_ID = $message->Add($arLoadProductArray))){
										echo "<p style='color:FF0000;'>Error: ".$message->LAST_ERROR."</p>";
										$error++;
									}
									else{
									  $arFieldsMes = array();
									  $arFieldsMes["EMAIL"] = $_POST["email_reciver_".$reciverProp];
									  CEvent::Send(
										  "ANEW_MESSAGE",
										  "ru",
										  $arFieldsMes
									  );
									}
								}
								if(!$error){
									echo "Все ваши сообщения были успешно отправленны";
								}
							}
							else{
								echo "<p>Вы не выбрали ни одного адресата!</p>";
							}
						}
					}
				?>
                <?
				}
				else{
				?>
                    <form action="/personal/shedule/admin/message_write.php" method="post" name="reg_update">
                    <h2 class="reg_title">Адресаты</h2>
                    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
                      <tr class="chet">
                        <td width="50"><input name="all_guest" type="checkbox" value="1" style="width:20px;"></td>
                        <td><strong>Всем гостям утренней сессии</strong></td>
                      </tr>
                      <tr>
                        <td><input name="all_particip" type="checkbox" value="1" style="width:20px;"></td>
                        <td><strong>Всем участникам</strong></td>
                      </tr>
                    </table>
                    <p class="reg_update" style="text-align:left;"><a href="#" onclick="toggle_desc('guest'); return false;">Гости утренней сессии</a></p>
                    <div id="desc_guest" style="display:none;">
                    <?
						$filter = Array
						(
							"GROUPS_ID"  => Array(16)
						);
						$rsGuests = CUser::GetList(($by="work_company"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // выбираем пользователей
						$is_filtered = $rsGuests->is_filtered; // отфильтрована ли выборка ?
						$counter = 0;
					?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
                        <tr class="chet">
                            <td width="50"><strong>Написать</strong></td>
                            <td><strong>Представитель и Компания</strong></td>
                            <td width="50"><strong>Написать</strong></td>
                            <td><strong>Представитель и Компания</strong></td>
                        </tr>
                        <tr>
						<?
						while($arParticip=$rsGuests->GetNext()) :
                            	if(!($counter % 2)){
									?>
                              </tr>
                              <tr <? if($counter % 4 == 2){?>class="chet"<? }?>>
									<?
								}
							?>
                                <td><input name="guests[]" type="checkbox" value="<?=$arParticip['ID']?>" style="width:20px;"><input name="reciver_<?=$arParticip['ID']?>" type="hidden" value="<?=$arParticip['NAME']?> <?=$arParticip['LAST_NAME']?> Компания <?=$arParticip['WORK_COMPANY']?>" /><input name="email_reciver_<?=$arParticip['ID']?>" type="hidden" value="<?=$arParticip['EMAIL']?>" /></td>
                                <td><?=$arParticip['NAME']?> <?=$arParticip['LAST_NAME']?><br />
                                	<strong><?=$arParticip['WORK_COMPANY']?></strong>
                                </td>
							<?
							$counter++;
						endwhile;
						?>
                        </tr>
                    </table>
                    </div>
                    <p class="reg_update" style="text-align:left;"><a href="#" onclick="toggle_desc('particip'); return false;">Участники</a></p>
                    <div id="desc_particip" style="display:none;">
                    <?
						$filter = Array
						(
							"GROUPS_ID"  => Array(4)
						);
						$rsParticip = CUser::GetList(($by="work_company"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // выбираем пользователей
						$is_filtered = $rsParticip->is_filtered; // отфильтрована ли выборка ?
						$counter = 0;
					?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
                        <tr class="chet">
                            <td width="50"><strong>Написать</strong></td>
                            <td><strong>Представитель и Компания</strong></td>
                            <td width="50"><strong>Написать</strong></td>
                            <td><strong>Представитель и Компания</strong></td>
                        </tr>
                        <tr>
						<?
						while($arParticip=$rsParticip->GetNext()) :
                            	if(!($counter % 2)){
									?>
                              </tr>
                              <tr <? if($counter % 4 == 2){?>class="chet"<? }?>>
									<?
								}
							?>
                                <td><input name="particip[]" type="checkbox" value="<?=$arParticip['ID']?>" style="width:20px;"><input name="reciver_<?=$arParticip['ID']?>" type="hidden" value="<?=$arParticip['NAME']?> <?=$arParticip['LAST_NAME']?> Компания <?=$arParticip['WORK_COMPANY']?>" /><input name="email_reciver_<?=$arParticip['ID']?>" type="hidden" value="<?=$arParticip['EMAIL']?>" /></td>
                                <td><?=$arParticip['NAME']?> <?=$arParticip['LAST_NAME']?><br />
                                	<strong><?=$arParticip['WORK_COMPANY']?></strong>
                                </td>
							<?
							$counter++;
						endwhile;
						?>
                        </tr>
                    </table>
                    </div>
                    <h2 class="reg_title">Сообщение</h2>
                    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
                      <tr class="chet">
                        <td width="200"><strong>ФИО</strong></td>
                        <td><? echo $arUser['NAME']." ".$arUser['LAST_NAME'];?><input name="fio" type="hidden" value="<? echo $arUser['NAME']." ".$arUser['LAST_NAME'];?>" /></td>
                      </tr>
                      <tr>
                        <td><strong>Компания</strong></td>
                        <td><?=$arUser['WORK_COMPANY']?><input name="company" type="hidden" value="<?=$arUser['WORK_COMPANY']?>" /></td>
                      </tr>
                      <tr class="chet">
                        <td><strong>Тема</strong></td>
                        <td><input name="subj" type="text" value="" /></td>
                      </tr>
                      <tr>
                        <td><strong>Текст сообщения</strong></td>
                        <td><textarea name="message_text"></textarea></td>
                      </tr>
                    </table>
                    <input name="mes" type="hidden" value="write" />
                    <div align="right"><input name="submit" type="submit" value="Отправить" class="send_reg" /></div>
                    </form>
                <?
				}
				?>
                </div>
            </div>
            <div style="height:10px;"></div>
	<?
    }
}
?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>