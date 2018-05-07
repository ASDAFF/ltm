<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Luxury Travel Mart - the leading luxury travel exhibition");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
global $USER;
global $DB;
CModule::IncludeModule("vote");

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

if (intval($_REQUEST["report"])>0) {

    $rsUser = $USER->GetByID($_REQUEST["report"]);
    $arUser = $rsUser->Fetch();
    $_SESSION["reports"]["COMPANY"] = $arUser["WORK_COMPANY"];

    $db_res = GetVoteDataByID(4, $arChannel, $arVote, $arQuestions, $arAnswers, $arDropDown, $arMultiSelect, $arGroupAnswers, "N");
    foreach ($arQuestions as $Q) {
        $result[$Q["ID"]]["ID"] = $Q["ID"];
        $result[$Q["ID"]]["QUESTION"] = $Q["QUESTION"];
        foreach ($Q["ANSWERS"] as $answ) {
            if ($answ["FIELD_TYPE"] == 0) $result[$Q["ID"]]["ANSWERS"][$answ["ID"]] = $answ["MESSAGE"];
            if ($answ["FIELD_TYPE"] == 4) if ($answ["MESSAGE"]=="Points") $result[$Q["ID"]]["ANSWERS"][$answ["ID"]] = $answ["MESSAGE"]; else $result[$Q["ID"]]["INNER"][$answ["ID"]] = $answ["MESSAGE"];
        }
    }

$k=0;
    $q = "SELECT * FROM `b_vote_event_answer` WHERE MESSAGE='".intval($_REQUEST["report"])."'";
    $res = $DB->Query($q, false, $err_mess.__LINE__);
    while ($row = $res->Fetch()) {
        $q2 = "SELECT * FROM `b_vote_event_question` WHERE ID='".$row["EVENT_QUESTION_ID"]."'";
        $res2 = $DB->Query($q2, false, $err_mess.__LINE__);
        while ($row2 = $res2->Fetch()) {
            $q3 = "SELECT * FROM `b_vote_event` WHERE ID='".$row2["EVENT_ID"]."'";
            $res3 = $DB->Query($q3, false, $err_mess.__LINE__);
            while ($row3 = $res3->Fetch()) {
                $arrUsr[$k]["USER_ID"] = $row3["VOTE_USER_ID"];
                $arrUsr[$k]["EVENT_ID"] = $row3["ID"];
                $k++;
            }
        }
    }



if (!empty($arrUsr)) {
    foreach ($arrUsr as $k=>$uid) {
        $q = "SELECT * FROM `b_vote_event` WHERE ID='" . intval($uid["EVENT_ID"]) . "'";
        $res = $DB->Query($q, false, $err_mess . __LINE__);
        while ($row = $res->Fetch()) {

            $_SESSION["reports"]["list"][$row["ID"]]["USER_ID"] = $row["VOTE_USER_ID"];

            $q2 = "SELECT * FROM `b_vote_event_question` WHERE EVENT_ID='" . $row["ID"] . "'";
            $res2 = $DB->Query($q2, false, $err_mess . __LINE__);
            while ($row2 = $res2->Fetch()) {
                $_SESSION["reports"]["list"][$row["ID"]][$row2["QUESTION_ID"]]["QUESTION"] = $result[$row2["QUESTION_ID"]]["QUESTION"];
                $q3 = "SELECT * FROM `b_vote_event_answer` WHERE EVENT_QUESTION_ID='" . $row2["ID"] . "'";
                $res3 = $DB->Query($q3, false, $err_mess . __LINE__);
                while ($row3 = $res3->Fetch()) {
                    if ($row3["MESSAGE"])
                        $_SESSION["reports"]["list"][$row["ID"]][$row2["QUESTION_ID"]]["ANSWER"] = $row3["MESSAGE"];
                    else
                        $_SESSION["reports"]["list"][$row["ID"]][$row2["QUESTION_ID"]]["ANSWER"] = $result[$row2["QUESTION_ID"]]["ANSWERS"][$row3["ANSWER_ID"]];
                }
            }
        }
    }
}



    foreach ($_SESSION["reports"]["list"] as $k=>$v) {
        foreach ($v as $k2=>$v2) {
            if ($k2=="USER_ID") {
                $rsUser = $USER->GetByID($v2);
                $arUser = $rsUser->Fetch();
                $_SESSION["reports"]["list"][$k]["USER"] = $arUser["UF_FIO"]." (".$arUser["WORK_COMPANY"].")";
                unset($_SESSION["reports"]["list"][$k]["USER_ID"]);
            }
        }
    }

    LocalRedirect("/rst/push/excel/reports_prepare.php");

}



if ($_REQUEST["checkin"]) {
    if ($_REQUEST["checkin"]=='all') {

        $filter = Array("GROUPS_ID" => Array($C_GUESTS_GROUP));
        //$filter["UF_HB"] = false;
        $rsUsers = CUser::GetList(($by="work_company"), ($order="asc"), $filter);
        while ($arUsers = $rsUsers->GetNext()) {
            $arF = array("UF_IS_ONLINE"=>"Y");
            $USER->Update($arUsers["ID"], $arF);
        }
    } else {
        if (intval($_REQUEST["checkin"])>0) {
            $arF = array("UF_IS_ONLINE"=>"Y");
            $USER->Update($_REQUEST["checkin"], $arF);
        }
    }
}

if ($_REQUEST["checkout"]) {
    if ($_REQUEST["checkout"]=='all') {

        $filter = Array("GROUPS_ID" => Array($C_GUESTS_GROUP));
        //$filter["UF_HB"] = false;
        $rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter);
        while ($arUsers = $rsUsers->GetNext()) {
            $arF = array("UF_IS_ONLINE"=>"");
            $USER->Update($arUsers["ID"], $arF);
        }
    } else {
        if (intval($_REQUEST["checkout"])>0) {
            $arF = array("UF_IS_ONLINE"=>"");
            $USER->Update($_REQUEST["checkout"], $arF);
        }
    }
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

        <?/*<div class="search_form"><a href="?exhb=<?=$_REQUEST["exhb"]?>&search_field=<?=$_REQUEST["search_field"]?>&ltr=<?=$_REQUEST["ltr"]?>&withreport=Y">Show with reports</a></div>*/?>




<div class="checkin_all"><a href="?ltr=<?=$_REQUEST["ltr"]?>&search_field=<?=$_REQUEST["search_field"]?>&exhb=<?=$_REQUEST["exhb"]?>&checkin=all">Check-in ALL</a> <a class="checkout_all" href="?ltr=<?=$_REQUEST["ltr"]?>&search_field=<?=$_REQUEST["search_field"]?>&exhb=<?=$_REQUEST["exhb"]?>&checkout=all">Check-out ALL</a></div>



<div class="info_table">
<table class="admin_table" >
    <tr>
        <th class="thcol1">Company</th>
        <th class="thcol2">Representative</th>
        <th class="thcol3">Contact</th>
        <th class="thcol4">Status</th>
        <th class="thcol5">Check-in</th>
        <th class="thcol5">Reports</th>
    </tr>
    
<?
$filter = Array("GROUPS_ID" => Array($C_GUESTS_GROUP));
if ($_REQUEST["ltr"]) {
    //UF_FIO
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
?>

    <tr>
        <td class="tdcol1"><a href="http://luxurytravelmart.ru/members/<?=$arUsers["UF_ID_COMP"]?>/"><?=htmlspecialchars_decode($arUsers["WORK_COMPANY"])?></a></td>
        <td class="tdcol2"><?=htmlspecialchars_decode($arUsers["UF_FIO"])?></td>
        <td class="tdcol3"><a href="/administrator/messages/new.php?exhb=<?=$_REQUEST["exhb"]?>&company=<?=urlencode($arUsers["WORK_COMPANY"])?>&TO_USER=<?=$arUsers["ID"]?>">Send a message</a></td>
        <td class="tdcol4">
            <? if ($arUsers["UF_IS_ONLINE"]) {?>
                <div class="green">on-line</div>
            <?}else{?>
                <div class="red">off-line</div>
            <?}?>
        </td>
        <td class="tdcol5">
            <? if ($arUsers["UF_IS_ONLINE"]) {?>
                <a href="?ltr=<?=$_REQUEST["ltr"]?>&search_field=<?=$_REQUEST["search_field"]?>&exhb=<?=$_REQUEST["exhb"]?>&checkout=<?=$arUsers["ID"]?>">Check-out</a>
            <?}else{?>
                <a href="?ltr=<?=$_REQUEST["ltr"]?>&search_field=<?=$_REQUEST["search_field"]?>&exhb=<?=$_REQUEST["exhb"]?>&checkin=<?=$arUsers["ID"]?>">Check-in</a>
            <?}?>
        </td>
        <td class="tdcol6">
            <?
            $q = "SELECT COUNT(ID) as cnt FROM `b_vote_event_answer` WHERE MESSAGE='".$arUsers["ID"]."'";
            $res = $DB->Query($q, false, $err_mess.__LINE__);
            while ($row = $res->Fetch()) {
                $cnt = $row["cnt"];
            }
            ?>
            <? if ($cnt>0) {?><a href="?ltr=<?=$_REQUEST["ltr"]?>&search_field=<?=$_REQUEST["search_field"]?>&exhb=<?=$_REQUEST["exhb"]?>&report=<?=$arUsers["ID"]?>">Reports (<?=$cnt?>)</a><?}else{?>Reports (<?=$cnt?>)<?}?>
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