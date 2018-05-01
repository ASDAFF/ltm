<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Luxury Travel Mart - the leading luxury travel exhibition");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
global $USER;
global $DB;

define('ADMIN_ID',5752);
?>



<? if ($USER->isAdmin()) {
    //if (intval($_REQUEST["mess"])==0 OR !intval($_REQUEST["mess"])) LocalRedirect("?exhb=".$_REQUEST["exhb"]);

    if ($_REQUEST["go"]) {
    
    if (empty($error)) {
        global $DB;
        
        $q = "INSERT INTO  `chat_admin_messages` (`ID`,`AUTHOR_ID`,`TO_USER`,`MESSAGE`,`COMPANY`,`DATE_CREATE`,`DELIVERED`) VALUES (NULL,'".ADMIN_ID."','".$_REQUEST["TO_USER"]."','".strip_tags($_REQUEST["message"])."','".strip_tags($_REQUEST["company"])."','".date("Y-m-d H:i:s", time())."','1')";
		$res = $DB->Query($q, false, $err_mess.__LINE__);
		
		
		if ($res->result) {

            $rsUser = $USER->GetByID($_REQUEST["TO_USER"]);
            $arUser = $rsUser->Fetch();

            $tokens = '';
            foreach ($arUser["UF_PUSH_TOKENS"] as $token) {
                $tokens .= 'push_tokens[]='.$token.'&';
            }
            $dev_ids = '';
            foreach ($arUser["UF_DEVICE_ID"] as $token) {
                $dev_ids .= 'device_ids[]='.$token.'&';
            }

            $options = array(
                'http'=>array(
                    'method'=>"GET",
                    'header'=>"Accept-language: en\r\n" .
                        "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36\r\n"
                )
            );
            $context = $file = '';
            $context = stream_context_create($options);
            $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess.php?user_id='.$_REQUEST["TO_USER"].'&alert='.urlencode(substr($_REQUEST["message"],0,70)).'&from_user_id='.ADMIN_ID.'&'.$tokens.'&'.$dev_ids, false, $context);
            $file2 = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess_android.php?user_id='.$_REQUEST["TO_USER"].'&alert='.urlencode(substr($_REQUEST["message"],0,70)).'&from_user_id='.ADMIN_ID.'&'.$tokens.'&'.$dev_ids, false, $context);


		    LocalRedirect("read.php?exhb=".$_REQUEST["exhb"]."&mess=".$_REQUEST["mess"]."&company=".$_REQUEST["company"]."&TO_USER=".$_REQUEST["TO_USER"]."&success=Your message have been sent.");
		}
    }
}
?>
    
    <div class="admin_block">
        <div class="helper_nav">
            <a href="/administrator/messages/?exhb=<?=$_REQUEST["exhb"]?>" class="selected">Inbox</a>
            <a href="/administrator/messages/sent.php?exhb=<?=$_REQUEST["exhb"]?>">Sent</a>
            <a href="/administrator/messages/new.php?exhb=<?=$_REQUEST["exhb"]?>">New message or notice</a>
        </div>

        <? if ($_REQUEST["success"]) {?><div class="green"><?=$_REQUEST["success"]?></div><?}?>
        <? if ($_REQUEST["go"] AND !empty($error)) {?><div class="red"><?=implode("<br>",$error)?></div><?}?>
        
      <form action="<?=$_SERVER["PHP_SELF"]?>" method="post">      
<div class="info_table">
<table class="admin_table" >
    <tr>
        <th class="thcol1">Theme</th>
        <th class="thcol2">Company</th>
        <th class="thcol3">Sender</th>
        <th class="thcol4">Date</th>
    </tr>
<?
$q = "UPDATE `chat_admin_messages` SET DELIVERED='1' WHERE `ID`='".$_REQUEST["mess"]."'";
$DB->Query($q, false, $err_mess.__LINE__);

$q = "SELECT * FROM  `chat_admin_messages` WHERE `ID`='".$_REQUEST["mess"]."' ORDER BY `DATE_CREATE` DESC";
$res = $DB->Query($q, false, $err_mess.__LINE__);
while ($row = $res->Fetch()) {

    $rsUser = CUser::GetByID($row["AUTHOR_ID"]);
    $arUser = $rsUser->Fetch();
?>
                <input type="hidden" name="COMPANY" value="<?=$row["COMPANY"]?>" />
                <input type="hidden" name="TO_USER" value="<?=$row["AUTHOR_ID"]?>" />
                <input type="hidden" name="mess" value="<?=$_REQUEST["mess"]?>" />
    <tr>
        <td class="tdcol1"><a href="read.php?exhb=<?=$_REQUEST["exhb"]?>&mess=<?=$row["ID"]?>"><?=$row["MESSAGE"]?></a></td>
        <td class="tdcol2"><?=$arUser["WORK_COMPANY"]?></td>
        <td class="tdcol3"><?=$arUser["UF_FIO"]?></td>
        <td class="tdcol4"><?=$row["DATE_CREATE"]?></td>
    </tr>
<?}?>
    
</table>
</div>        
        <div class="form_holder">
                <input type="hidden" name="exhb" value="<?=$_REQUEST["exhb"]?>" >
                <div class="message">
                    <textarea name="message"><?=$_REQUEST["message"]?></textarea>
                </div>
                <div class="submit"><input type="submit" name="go" value="Answer" /></div>
            
        </div>
        
        </form>
        
    </div>
    
<?}else{?>

<?$APPLICATION->IncludeComponent(
	"btm:user.login",
	"",
	Array(
		"REGISTER_URL" => "",
		"GUEST_URL" => "",
		"PARTICIP_URL" => "",
		"ADMIN_URL" => "",
		"SHOW_ERRORS" => "N"
	),
false
);?>

<?}?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>