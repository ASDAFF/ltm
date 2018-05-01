<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Luxury Travel Mart - the leading luxury travel exhibition");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
global $USER;

?>



<? if ($USER->isAdmin()) {?>
    
    <div class="admin_block">
    
<div class="alphabet">
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=other" <? if ($_REQUEST["exhb"]=="other") {?>class="selected"<?}?>>#</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=A" <? if ($_REQUEST["exhb"]=="A") {?>class="selected"<?}?>>A</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=B" <? if ($_REQUEST["exhb"]=="B") {?>class="selected"<?}?>>B</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=C" <? if ($_REQUEST["exhb"]=="C") {?>class="selected"<?}?>>C</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=D" <? if ($_REQUEST["exhb"]=="D") {?>class="selected"<?}?>>D</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=E" <? if ($_REQUEST["exhb"]=="E") {?>class="selected"<?}?>>E</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=F" <? if ($_REQUEST["exhb"]=="F") {?>class="selected"<?}?>>F</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=G" <? if ($_REQUEST["exhb"]=="G") {?>class="selected"<?}?>>G</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=H" <? if ($_REQUEST["exhb"]=="H") {?>class="selected"<?}?>>H</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=I" <? if ($_REQUEST["exhb"]=="I") {?>class="selected"<?}?>>I</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=J" <? if ($_REQUEST["exhb"]=="J") {?>class="selected"<?}?>>J</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=K" <? if ($_REQUEST["exhb"]=="K") {?>class="selected"<?}?>>K</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=L" <? if ($_REQUEST["exhb"]=="L") {?>class="selected"<?}?>>L</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=M" <? if ($_REQUEST["exhb"]=="M") {?>class="selected"<?}?>>M</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=N" <? if ($_REQUEST["exhb"]=="N") {?>class="selected"<?}?>>N</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=O" <? if ($_REQUEST["exhb"]=="O") {?>class="selected"<?}?>>O</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=P" <? if ($_REQUEST["exhb"]=="P") {?>class="selected"<?}?>>P</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=Q" <? if ($_REQUEST["exhb"]=="Q") {?>class="selected"<?}?>>Q</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=R" <? if ($_REQUEST["exhb"]=="R") {?>class="selected"<?}?>>R</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=S" <? if ($_REQUEST["exhb"]=="S") {?>class="selected"<?}?>>S</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=T" <? if ($_REQUEST["exhb"]=="T") {?>class="selected"<?}?>>T</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=U" <? if ($_REQUEST["exhb"]=="U") {?>class="selected"<?}?>>U</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=V" <? if ($_REQUEST["exhb"]=="V") {?>class="selected"<?}?>>V</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=W" <? if ($_REQUEST["exhb"]=="W") {?>class="selected"<?}?>>W</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=X" <? if ($_REQUEST["exhb"]=="X") {?>class="selected"<?}?>>X</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=Y" <? if ($_REQUEST["exhb"]=="Y") {?>class="selected"<?}?>>Y</a>
    <a href="?exhb=<?=$_REQUEST["exhb"]?>&ltr=Z" <? if ($_REQUEST["exhb"]=="Z") {?>class="selected"<?}?>>Z</a>
</div>

<div class="checkin_all"><a href="?exhb=<?=$_REQUEST["exhb"]?>&checkin=all">Check-in ALL</a></div>
    
    
<?
$arSelect = Array("ID", "NAME", "PROPERTY_USER_GROUP_ID", "PROPERTY_UC_PARTICIPANTS_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP");
$arFilter = Array("IBLOCK_ID"=>15, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$_REQUEST["exhb"]);
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $USER_GROUP_ID = $arFields["PROPERTY_USER_GROUP_ID_VALUE"];
    $UC_PARTICIPANTS_GROUP = $arFields["PROPERTY_UC_PARTICIPANTS_GROUP_VALUE"];
    $C_GUESTS_GROUP = $arFields["PROPERTY_C_GUESTS_GROUP_VALUE"];
    $UC_GUESTS_GROUP = $arFields["PROPERTY_UC_GUESTS_GROUP_VALUE"];
}
?>
    
    
<div class="info_table">
<table class="admin_table" >
    <tr>
        <th>Company</th>
        <th>Representative</th>
        <th>Contact</th>
        <th>Status</th>
        <th>Check-in</th>
    </tr>
    
<?
$filter = Array("GROUPS_ID" => Array($USER_GROUP_ID));
$rsUsers = CUser::GetList(($by="work_company"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
$rsUsers->NavStart(20);
while ($arUsers = $rsUsers->GetNext()) {
?>

    <tr>
        <td><a href="#"><?=$arUsers["WORK_COMPANY"]?></a></td>
        <td><?=$arUsers["UF_FIO"]?></td>
        <td><a href="?exhb=<?=$_REQUEST["exhb"]?>&mess=<?=$arUsers["ID"]?>">Send a message</a></td>
        <td>
            <? if ($arUsers["IS_ONLINE"]=="Y") {?>
                <div class="green">on-line</div>
            <?}else{?>
                <div class="red">off-line</div>
            <?}?>
        </td>
        <td><a href="?exhb=<?=$_REQUEST["exhb"]?>&checkin=<?=$arUsers["ID"]?>">Check-in</a></td>
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