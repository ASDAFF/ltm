<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Luxury Travel Mart - the leading luxury travel exhibition");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
global $USER;

define('ADMIN_ID',5752);


$arSelect = Array("ID", "NAME", "PROPERTY_USER_GROUP_ID", "PROPERTY_UC_PARTICIPANTS_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP");
$arFilter = Array("IBLOCK_ID"=>15, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$_REQUEST["exhb"]);
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $USER_GROUP_ID = $arFields["PROPERTY_USER_GROUP_ID_VALUE"];
    $UC_PARTICIPANTS_GROUP = $arFields["PROPERTY_UC_PARTICIPANTS_GROUP_VALUE"];
    $C_GUESTS_GROUP = $arFields["PROPERTY_C_GUESTS_GROUP_VALUE"];
    $UC_GUESTS_GROUP = $arFields["PROPERTY_UC_GUESTS_GROUP_VALUE"];
    $_SESSION["reports"]["exhibition_name"] = $arFields["NAME"];
}
?>



<? if ($USER->isAdmin()) {?>
    
<?

if ($_REQUEST["go"]) {
    
    if (!$_REQUEST["message"]) $error[] = "Message field required.";
    if (!$_REQUEST["company"]) $error[] = "Company field required.";
    if (!$_REQUEST["TO_USER"] AND !$_REQUEST["to_all"]) $error[] = "To user or to all field required.";
    
    if (empty($error)) {
        global $DB;
        //iconv("UTF-8","CP1251",$_REQUEST["message"])
        //".$USER->GetId()."

        if ($_REQUEST["to_all"]) {

            $filter = Array("GROUPS_ID" => Array($USER_GROUP_ID,$C_GUESTS_GROUP), "ACTIVE"=>"Y");
            $rsUsers = CUser::GetList(($by="work_company"), ($order="asc"), $filter);
            while ($arUsers = $rsUsers->GetNext()) {


                $q = "INSERT INTO  `chat_admin_messages` (`ID`,`AUTHOR_ID`,`TO_USER`,`MESSAGE`,`COMPANY`,`DATE_CREATE`,`DELIVERED`) VALUES (NULL,'" . ADMIN_ID . "','" . $arUsers["ID"] . "','" . strip_tags(addslashes($_REQUEST["message"])) . "','" . strip_tags(iconv("UTF-8", "CP1251", addslashes($arUsers["WORK_COMPANY"]))) . "','" . date("Y-m-d H:i:s", time()) . "','1')";
                $res = $DB->Query($q, false, $err_mess . __LINE__);


                if ($res->result) {

                    $rsUser = $USER->GetByID($arUsers["ID"]);
                    $arUser = $rsUser->Fetch();

                    $tokens = '';
                    foreach ($arUser["UF_PUSH_TOKENS"] as $token) {
                        $tokens .= 'push_tokens[]=' . $token . '&';
                    }
                    $dev_ids = '';
                    foreach ($arUser["UF_DEVICE_ID"] as $token) {
                        $dev_ids .= 'device_ids[]=' . $token . '&';
                    }

                    $options = array(
                        'http' => array(
                            'method' => "GET",
                            'header' => "Accept-language: en\r\n" .
                                "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36\r\n"
                        )
                    );
                    $context = $file = '';
                    $context = stream_context_create($options);
                    $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess.php?' . $tokens . '&' . $dev_ids . '&user_id=' . ADMIN_ID . '&user_name=Administrator&alert=' . urlencode(substr($_REQUEST["message"], 0, 70)) . '&from_user_id=' . ADMIN_ID, false, $context);
                    $file2 = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess_android.php?' . $tokens . '&' . $dev_ids . '&user_id=' . ADMIN_ID . '&user_name=Administrator&alert=' . urlencode(substr($_REQUEST["message"], 0, 70)) . '&from_user_id=' . ADMIN_ID, false, $context);
                    //echo $file2;


                }
            }



            LocalRedirect("new.php?exhb=" . $_REQUEST["exhb"] . "&company=" . $_REQUEST["company"] . "&TO_USER=" . $_REQUEST["TO_USER"] . "&success=Your message have been sent.");



        } else {


            $q = "INSERT INTO  `chat_admin_messages` (`ID`,`AUTHOR_ID`,`TO_USER`,`MESSAGE`,`COMPANY`,`DATE_CREATE`,`DELIVERED`) VALUES (NULL,'" . ADMIN_ID . "','" . $_REQUEST["TO_USER"] . "','" . strip_tags(addslashes($_REQUEST["message"])) . "','" . strip_tags(iconv("UTF-8", "CP1251", $_REQUEST["company"])) . "','" . date("Y-m-d H:i:s", time()) . "','1')";
            $res = $DB->Query($q, false, $err_mess . __LINE__);


            if ($res->result) {

                $rsUser = $USER->GetByID($_REQUEST["TO_USER"]);
                $arUser = $rsUser->Fetch();

                $tokens = '';
                foreach ($arUser["UF_PUSH_TOKENS"] as $token) {
                    $tokens .= 'push_tokens[]=' . $token . '&';
                }
                $dev_ids = '';
                foreach ($arUser["UF_DEVICE_ID"] as $token) {
                    $dev_ids .= 'device_ids[]=' . $token . '&';
                }

                $options = array(
                    'http' => array(
                        'method' => "GET",
                        'header' => "Accept-language: en\r\n" .
                            "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36\r\n"
                    )
                );
                $context = $file = '';
                $context = stream_context_create($options);
                $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess.php?' . $tokens . '&' . $dev_ids . '&user_id=' . $_REQUEST["TO_USER"] . '&user_name=' . $arUser["NAME"] . '&alert=' . urlencode(substr($_REQUEST["message"], 0, 70)) . '&from_user_id=' . ADMIN_ID, false, $context);
                $file2 = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess_android.php?' . $tokens . '&' . $dev_ids . '&user_id=' . ADMIN_ID . '&user_name=' . $arUser["NAME"] . '&alert=' . urlencode(substr($_REQUEST["message"], 0, 70)) . '&from_user_id=' . ADMIN_ID, false, $context);
                //echo $file2;


                LocalRedirect("new.php?exhb=" . $_REQUEST["exhb"] . "&company=" . $_REQUEST["company"] . "&TO_USER=" . $_REQUEST["TO_USER"] . "&success=Your message have been sent.");
            }

        }
		
        
        
    }
    
}

$filter = Array();
$rsUsers = CUser::GetList(($by="work_company"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
while ($arUsers = $rsUsers->GetNext()) {
    if ($arUsers["WORK_COMPANY"])
        $companies[trim($arUsers["WORK_COMPANY"])] = trim($arUsers["WORK_COMPANY"]);
}
?>
    
    
    
    <div class="admin_block">
        <div class="helper_nav">
            <a href="/administrator/messages/?exhb=<?=$_REQUEST["exhb"]?>">Inbox</a>
            <a href="/administrator/messages/sent.php?exhb=<?=$_REQUEST["exhb"]?>">Sent</a>
            <a href="/administrator/messages/new.php?exhb=<?=$_REQUEST["exhb"]?>" class="selected">New message or notice</a>
        </div>
        
        <div class="form_holder">
        
<? if ($_REQUEST["success"]) {?><div class="green"><?=$_REQUEST["success"]?></div><?}?>
<? if ($_REQUEST["go"] AND !empty($error)) {?><div class="red"><?=implode("<br>",$error)?></div><?}?>
        
            <form action="<?=$_SERVER["PHP_SELF"]?>" method="post">
            <input type="hidden" name="exhb" value="<?=$_REQUEST["exhb"]?>" >
                <table class="sent_mess_table">
                    <tr>
                        <td class="sent_mess_table_col1">
                            <div class="select_wrap">
                                <span>Company:</span>
                                <select name="company" id="company" onchange="loadUser($('#company option:selected').val());">
                                    <option value=""> -- Select -- </option>
                                    <? foreach ($companies as $company) {?>
                                        <option value="<?=urlencode($company)?>" <? if ($_REQUEST["company"]==urlencode($company) OR $_REQUEST["company"]==$company) {?>selected="selected"<?}?>><?=$company?></option>
                                    <?}?>
                                </select>
                            </div>
                            <div class="select_wrap">
                                <span>To:</span>
                                <select name="TO_USER" id="TO_USER">
                                    <option value=""> -- Select -- </option>
                                    <? if ($_REQUEST["company"]) {?>
<?
$filter = Array("WORK_COMPANY"=>(urldecode($_REQUEST["company"])));
$rsUsers = CUser::GetList(($by="UF_FIO"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
while ($arUsers = $rsUsers->GetNext()) {
?>
   <option value="<?=$arUsers["ID"]?>" <? if ($_REQUEST["TO_USER"]==$arUsers["ID"]) {?>selected="selected"<?}?>><?=$arUsers["UF_FIO"]?></option>
<?}?> 
                                    <?}?>
                                </select>
                            </div>
                        </td>
                        <td class="sent_mess_table_col2">
                            <input type="checkbox" name="to_all" value="1" <? if ($_REQUEST["to_all"]) {?>checked="checked"<?}?> /> SEND NOTICE TO EVERYONE
                        </td>
                    </tr>
                </table>
                <div class="message">
                    <textarea name="message"><?=$_REQUEST["message"]?></textarea>
                </div>
                <div class="submit"><input type="submit" name="go" value="Send" /></div>
            </form>
        </div>
        
    <script type="text/javascript">
        function loadUser(id) {
            $.ajax({ 
                url: 'contacts_ajax.php?comp='+id, 
                success: function(data) { 
                    $('#TO_USER').empty().html(data);
                },
                error: function(msg) { }
            });
        }
    </script>
    
    
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