<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Luxury Travel Mart - the leading luxury travel exhibition");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
global $USER;
global $DB;

$arSelect = Array("ID", "NAME", "PROPERTY_USER_GROUP_ID", "PROPERTY_UC_PARTICIPANTS_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP");
$arFilter = Array("IBLOCK_ID"=>15, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$_REQUEST["exhb"]);
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    define('APP_EXHIBITOR', $arFields["PROPERTY_USER_GROUP_ID_VALUE"]);
    define('APP_BUYER', $arFields["PROPERTY_C_GUESTS_GROUP_VALUE"]);
}

$arrMonth = array("01"=>"january", "02"=>"february", "03"=>"march", "04"=>"april", "05"=>"may", "06"=>"june", "07"=>"july", "08"=>"august", "09"=>"september", "10"=>"october", "11"=>"november", "12"=>"december" );

$arSelect = Array("ID", "NAME", "PROPERTY_USER_GROUP_ID", "PROPERTY_UC_PARTICIPANTS_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP");
$arFilter = Array("IBLOCK_ID"=>15, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$_REQUEST["exhb"]);
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $USER_GROUP_ID = $arFields["PROPERTY_USER_GROUP_ID_VALUE"];
    $UC_PARTICIPANTS_GROUP = $arFields["PROPERTY_UC_PARTICIPANTS_GROUP_VALUE"];
    $C_GUESTS_GROUP = $arFields["PROPERTY_C_GUESTS_GROUP_VALUE"];
    $UC_GUESTS_GROUP = $arFields["PROPERTY_UC_GUESTS_GROUP_VALUE"];
    $_SESSION["no_show_report"]["exhibition_name"] = $arFields["NAME"];
}



if ($_REQUEST["report"] AND intval($_REQUEST["user"])>0) {

    $k=0;
    $_SESSION["no_show_report"]["reports"] = 0;
    $q = "SELECT * FROM `no_show_reports` WHERE `RECEIVER_ID`='".$_REQUEST["user"]."'";
    $res = $DB->Query($q, false, $err_mess.__LINE__);
    while ($row = $res->Fetch()) {

        unset($tmp0);
        unset($tmp1);
        unset($tmp2);
        $tmp0 = explode(" ", $row["REPORT_TIME"]);
        $tmp1 = explode(":", $tmp0[1]);
        $tmp2 = explode("-", $tmp0[0]);

        $_SESSION["no_show_report"]["list"][strtotime($row["REPORT_TIME"])."_".$k]["RAW_TIME"] = strtotime($row["REPORT_TIME"]);
        $_SESSION["no_show_report"]["list"][strtotime($row["REPORT_TIME"])."_".$k]["REP_TIME"] = $tmp1[0].":".$tmp1[1];
        $_SESSION["no_show_report"]["list"][strtotime($row["REPORT_TIME"])."_".$k]["REP_DATE"] = $tmp2[2]." ".$arrMonth[$tmp2[1]]." ".$tmp2[0];

        $filter = Array("ID"=>$row["SENDER_ID"]);
        $rsUsers = CUser::GetList(($by="UF_FIO"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
        while ($arUsers = $rsUsers->GetNext()) {
            $_SESSION["no_show_report"]["list"][strtotime($row["REPORT_TIME"])."_".$k]["COMPANY"] = $arUsers["WORK_COMPANY"];
            $_SESSION["no_show_report"]["list"][strtotime($row["REPORT_TIME"])."_".$k]["FIO"] = $arUsers["UF_FIO"];
        }

        $k++;
        $_SESSION["no_show_report"]["reports"]++;
    }

    ksort($_SESSION["no_show_report"]["list"]);
    $arTmp = $_SESSION["no_show_report"]["list"];
    unset($_SESSION["no_show_report"]["list"]);
    foreach ($arTmp as $itm) {
        $_SESSION["no_show_report"]["list"][] = $itm;
    }


    $filter = Array("ID"=>$row["RECEIVER_ID"]);
    $rsUsers = CUser::GetList(($by="UF_FIO"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
    while ($arUsers = $rsUsers->GetNext()) {
        $_SESSION["no_show_report"]["COMPANY_WHO"] = $arUsers["WORK_COMPANY"];
        $_SESSION["no_show_report"]["FIO_WHO"] = $arUsers["UF_FIO"];
    }

        LocalRedirect("/rst/push/excel/noshow_prepare.php");


}



if ($_REQUEST["allreport"]) {

    $k=0;
    $_SESSION["no_show_report"]["reports"] = 0;
    $q = "SELECT * FROM `no_show_reports` ORDER BY `RECEIVER_ID` ASC";
    $res = $DB->Query($q, false, $err_mess.__LINE__);
    while ($row = $res->Fetch()) {

        unset($tmp0);
        unset($tmp1);
        unset($tmp2);
        $tmp0 = explode(" ", $row["REPORT_TIME"]);
        $tmp1 = explode(":", $tmp0[1]);
        $tmp2 = explode("-", $tmp0[0]);

        $_SESSION["no_show_report"]["list"][strtotime($row["REPORT_TIME"])."_".$k]["RAW_TIME"] = strtotime($row["REPORT_TIME"]);
        $_SESSION["no_show_report"]["list"][strtotime($row["REPORT_TIME"])."_".$k]["REP_TIME"] = $tmp1[0].":".$tmp1[1];
        $_SESSION["no_show_report"]["list"][strtotime($row["REPORT_TIME"])."_".$k]["REP_DATE"] = $tmp2[2]." ".$arrMonth[$tmp2[1]]." ".$tmp2[0];

        $filter = Array("ID"=>$row["SENDER_ID"]);
        $rsUsers = CUser::GetList(($by="UF_FIO"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
        while ($arUsers = $rsUsers->GetNext()) {
            $_SESSION["no_show_report"]["list"][strtotime($row["REPORT_TIME"])."_".$k]["COMPANY"] = $arUsers["WORK_COMPANY"];
            $_SESSION["no_show_report"]["list"][strtotime($row["REPORT_TIME"])."_".$k]["FIO"] = $arUsers["UF_FIO"];
        }

        $k++;
        $_SESSION["no_show_report"]["reports"]++;
    }

    ksort($_SESSION["no_show_report"]["list"]);
    $arTmp = $_SESSION["no_show_report"]["list"];
    unset($_SESSION["no_show_report"]["list"]);
    foreach ($arTmp as $itm) {
        $_SESSION["no_show_report"]["list"][] = $itm;
    }


    $filter = Array("ID"=>$row["RECEIVER_ID"]);
    $rsUsers = CUser::GetList(($by="UF_FIO"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
    while ($arUsers = $rsUsers->GetNext()) {
        $_SESSION["no_show_report"]["COMPANY_WHO"] = $arUsers["WORK_COMPANY"];
        $_SESSION["no_show_report"]["FIO_WHO"] = $arUsers["UF_FIO"];
    }

    LocalRedirect("/rst/push/excel/noshow_prepare.php");


}




?>



<? if ($USER->isAdmin()) {?>
    
    <div class="admin_block">
    
<div class="alphabet">
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=other" <? if ($_REQUEST["ltr"]=="other") {?>class="selected"<?}?>>#</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=A" <? if ($_REQUEST["ltr"]=="A") {?>class="selected"<?}?>>A</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=B" <? if ($_REQUEST["ltr"]=="B") {?>class="selected"<?}?>>B</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=C" <? if ($_REQUEST["ltr"]=="C") {?>class="selected"<?}?>>C</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=D" <? if ($_REQUEST["ltr"]=="D") {?>class="selected"<?}?>>D</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=E" <? if ($_REQUEST["ltr"]=="E") {?>class="selected"<?}?>>E</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=F" <? if ($_REQUEST["ltr"]=="F") {?>class="selected"<?}?>>F</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=G" <? if ($_REQUEST["ltr"]=="G") {?>class="selected"<?}?>>G</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=H" <? if ($_REQUEST["ltr"]=="H") {?>class="selected"<?}?>>H</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=I" <? if ($_REQUEST["ltr"]=="I") {?>class="selected"<?}?>>I</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=J" <? if ($_REQUEST["ltr"]=="J") {?>class="selected"<?}?>>J</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=K" <? if ($_REQUEST["ltr"]=="K") {?>class="selected"<?}?>>K</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=L" <? if ($_REQUEST["ltr"]=="L") {?>class="selected"<?}?>>L</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=M" <? if ($_REQUEST["ltr"]=="M") {?>class="selected"<?}?>>M</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=N" <? if ($_REQUEST["ltr"]=="N") {?>class="selected"<?}?>>N</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=O" <? if ($_REQUEST["ltr"]=="O") {?>class="selected"<?}?>>O</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=P" <? if ($_REQUEST["ltr"]=="P") {?>class="selected"<?}?>>P</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=Q" <? if ($_REQUEST["ltr"]=="Q") {?>class="selected"<?}?>>Q</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=R" <? if ($_REQUEST["ltr"]=="R") {?>class="selected"<?}?>>R</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=S" <? if ($_REQUEST["ltr"]=="S") {?>class="selected"<?}?>>S</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=T" <? if ($_REQUEST["ltr"]=="T") {?>class="selected"<?}?>>T</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=U" <? if ($_REQUEST["ltr"]=="U") {?>class="selected"<?}?>>U</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=V" <? if ($_REQUEST["ltr"]=="V") {?>class="selected"<?}?>>V</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=W" <? if ($_REQUEST["ltr"]=="W") {?>class="selected"<?}?>>W</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=X" <? if ($_REQUEST["ltr"]=="X") {?>class="selected"<?}?>>X</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=Y" <? if ($_REQUEST["ltr"]=="Y") {?>class="selected"<?}?>>Y</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=Z" <? if ($_REQUEST["ltr"]=="Z") {?>class="selected"<?}?>>Z</a>
</div>

<div class="search_form">
    <form action="<?=$_SERVER["PHP_SELF"]?>" method="get" >
        <input type="hidden" name="exhb" value="<?=$_REQUEST["exhb"]?>" />
        <input type="text" name="search_field" value="<?=$_REQUEST["search_field"]?>" class="search_field" placeholder="Search" /><input type="submit" name="go_find" class="go_find" value="OK" />
    </form>
</div>
    <div class="search_form"><a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=<?=$_REQUEST["ltr"]?>&allreport=Y">Download all reports</a></div>

    
<div class="info_table">
<table class="admin_table" >
    <tr>
        <th class="thcol1">Company</th>
        <th class="thcol2">Representative</th>
        <th class="thcol3">Contact</th>
        <th class="thcol4">Status</th>
    </tr>
    
<?
$filter = Array("GROUPS_ID" => Array(APP_EXHIBITOR,APP_BUYER));
if ($_REQUEST["ltr"]) {
    $filter["WORK_COMPANY"] = $_REQUEST["ltr"]."%";
    $filter["WORK_COMPANY_EXACT_MATCH"] = "Y";
}
if ($_REQUEST["search_field"]) {
    $filter["KEYWORDS"] = $_REQUEST["search_field"];
}

//$filter["UF_HB"] = false;
$rsUsers = CUser::GetList(($by="work_company"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
$rsUsers->NavStart(50);
while ($arUsers = $rsUsers->GetNext()) {

    $reports = 0;
    $q = "SELECT id FROM `no_show_reports` WHERE `RECEIVER_ID`='".$arUsers["ID"]."'";
    $res = $DB->Query($q, false, $err_mess.__LINE__);
    while ($row = $res->Fetch()) {
        $reports++;
    }
?>

    <tr>
        <td class="tdcol1"><a href="http://luxurytravelmart.ru/members/<?=$arUsers["UF_ID_COMP"]?>/"><?=$arUsers["WORK_COMPANY"]?></a></td>
        <td class="tdcol2"><?=$arUsers["UF_FIO"]?></td>
        <td class="tdcol3"><a href="/administrator/messages/new.php?exhb=<?=$_REQUEST["exhb"]?>&company=<?=urlencode($arUsers["WORK_COMPANY"])?>&TO_USER=<?=$arUsers["ID"]?>">Send a message</a></td>
        <td class="tdcol4">
            <? if ($reports==0) {?>
                <div class="green"><a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=<?=$_REQUEST["ltr"]?>&report=Y&user=<?=$arUsers["ID"]?>">Reports (<?=$reports?>)</a></div>
            <?}else{?>
                <div class="red"><a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=<?=$_REQUEST["ltr"]?>&report=Y&user=<?=$arUsers["ID"]?>">Reports (<?=$reports?>)</a></div>
            <?}?>
        </td>
    </tr>
<?}?>  

</table>

<div class="pagin"><?=$rsUsers->NavPrint(GetMessage("PAGES"));?></div>


</div>
    
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