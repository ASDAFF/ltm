<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Luxury Travel Mart - the leading luxury travel exhibition");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
global $USER;
global $DB;

define('ADMIN_ID',5752);

$filter = Array();
$rsUsers = CUser::GetList(($by="work_company"), ($order="asc"), $filter);
while ($arUsers = $rsUsers->GetNext()) {
    if ($arUsers["WORK_COMPANY"])
        $companies[trim($arUsers["WORK_COMPANY"])] = trim($arUsers["WORK_COMPANY"]);
}
?>



<? if ($USER->isAdmin()) {?>
    
    <div class="admin_block">
        <div class="helper_nav">
            <a href="/administrator/messages/?exhb=<?=$_REQUEST["exhb"]?>">Inbox</a>
            <a href="/administrator/messages/sent.php?exhb=<?=$_REQUEST["exhb"]?>" class="selected">Sent</a>
            <a href="/administrator/messages/new.php?exhb=<?=$_REQUEST["exhb"]?>">New message or notice</a>
        </div>

    <div class="form_holder">
        <table class="sent_mess_table">
            <tr>
                <td class="sent_mess_table_col1" style="border-right: none;">
                    <div class="select_wrap">
                        <span>Company:</span>
                        <select name="company" id="company" onchange="loadUser($('#company option:selected').val());">
                            <option value=""> -- Select -- </option>
                            <? foreach ($companies as $company) {?>
                                <option value="<?=urlencode($company)?>" <? if ($_REQUEST["company"]==urlencode($company)) {?>selected="selected"<?}?>><?=$company?></option>
                            <?}?>
                        </select>
                    </div>
                    <div class="select_wrap">
                        <span>To:</span>
                        <select name="TO_USER" id="TO_USER" onchange="location.href='/administrator/messages/sent.php?exhb=<?=$_REQUEST["exhb"]?>&company='+$('#company').val()+'&AUTHOR_ID='+$('#AUTHOR_ID').val()">
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
            </tr>
        </table>
    </div>


            
<div class="info_table">
<table class="admin_table" >
    <tr>
        <th class="thcol1">Theme</th>
        <th class="thcol2">Company</th>
        <th class="thcol3">Sender</th>
        <th class="thcol4">Date</th>
    </tr>
<?

if (intval($_REQUEST["TO_USER"])>0)
    $to = "TO_USER='".$_REQUEST["TO_USER"]."' AND ";

$q = "SELECT * FROM  `chat_admin_messages` WHERE ".$to." `AUTHOR_ID`='".ADMIN_ID."' ORDER BY `DATE_CREATE` DESC LIMIT 0,1000";
$res = $DB->Query($q, false, $err_mess.__LINE__);
while ($row = $res->Fetch()) {
    $rsUser = CUser::GetByID($row["TO_USER"]);
    $arUser = $rsUser->Fetch();
?>
    <tr>
        <td class="tdcol1"><a href="read.php?exhb=<?=$_REQUEST["exhb"]?>&mess=<?=$row["ID"]?>"><?=$row["MESSAGE"]?></a></td>
        <td class="tdcol2"><?=$arUser["WORK_COMPANY"]?></td>
        <td class="tdcol3">
        <?=$arUser["UF_FIO"]?>
        </td>
        <td class="tdcol4"><?=$row["DATE_CREATE"]?></td>
    </tr>
<?}?>
    
</table>
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