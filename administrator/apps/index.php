<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Luxury Travel Mart - the leading luxury travel exhibition");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
global $USER;

$_SESSION["messages_report"] = '';
unset($_SESSION["messages_report"]);


$arSelect = Array("ID", "NAME", "PROPERTY_USER_GROUP_ID", "PROPERTY_UC_PARTICIPANTS_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP");
$arFilter = Array("IBLOCK_ID"=>15, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$_REQUEST["exhb"]);
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $USER_GROUP_ID = $arFields["PROPERTY_USER_GROUP_ID_VALUE"];
    $UC_PARTICIPANTS_GROUP = $arFields["PROPERTY_UC_PARTICIPANTS_GROUP_VALUE"];
    $C_GUESTS_GROUP = $arFields["PROPERTY_C_GUESTS_GROUP_VALUE"];
    $UC_GUESTS_GROUP = $arFields["PROPERTY_UC_GUESTS_GROUP_VALUE"];
    $_SESSION["messages_report"]["exhibition"]["id"] = $_REQUEST["exhb"];
    $_SESSION["messages_report"]["exhibition"]["name"] = $arFields["NAME"];
}

?>



<? if ($USER->isAdmin()) {

$filter = Array();
$rsUsers = CUser::GetList(($by="work_company"), ($order="asc"), $filter);
while ($arUsers = $rsUsers->GetNext()) {
    if ($arUsers["WORK_COMPANY"])
        $companies[trim($arUsers["WORK_COMPANY"])] = trim($arUsers["WORK_COMPANY"]);
}

    
if ($_REQUEST["go"]) {
    
    if (!$_REQUEST["company_from"]) $error[] = "From company message field required";
    if (!$_REQUEST["company_to"]) $error[] = "To company message field required";

    if (empty($error)) {
        global $DB;

        $arSelect = Array("ID", "NAME", "PROPERTY_USER_GROUP_ID", "PROPERTY_UC_PARTICIPANTS_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP");
        $arFilter = Array("IBLOCK_ID"=>15, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$_REQUEST["exhb"]);
        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>1), $arSelect);
        while($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $_SESSION["reports"]["exhibition"]["name"] = $arFields["NAME"];
        }


        $_SESSION["messages_report"]["company_from"] = urldecode($_REQUEST["company_from"]);
        $_SESSION["messages_report"]["company_to"] = urldecode($_REQUEST["company_to"]);

        $filter = Array("WORK_COMPANY"=>(urldecode($_REQUEST["company_from"])));
        $rsUsers = CUser::GetList(($by="UF_FIO"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
        while ($arUsers = $rsUsers->GetNext()) {
            $company_from = $arUsers["ID"];
            $_SESSION["messages_report"]["company_from_id"] = $arUsers["ID"];
            $_SESSION["messages_report"]["user_from"] = $arUsers["UF_FIO"];
        }

        $filter = Array("WORK_COMPANY"=>(urldecode($_REQUEST["company_to"])));
        $rsUsers = CUser::GetList(($by="UF_FIO"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
        while ($arUsers = $rsUsers->GetNext()) {
            $company_to = $arUsers["ID"];
            $_SESSION["messages_report"]["company_to_id"] = $arUsers["ID"];
            $_SESSION["messages_report"]["user_to"] = $arUsers["UF_FIO"];
        }

        $q = "SELECT * FROM  `chat_messages` WHERE (`TO_USER`='".$company_to."' AND `AUTHOR_ID`='".$company_from."') OR (`TO_USER`='".$company_from."' AND `AUTHOR_ID`='".$company_to."') ORDER BY `ID` DESC";
        $res = $DB->Query($q, false, $err_mess.__LINE__);
        while ($row = $res->Fetch()) {
            $_SESSION["messages_report"]["mess"][] = $row;
        }

        LocalRedirect("/rst/push/excel/messages_prepare.php");
    }
    
}
    
    
    
?>
    
    <div class="admin_block">
    

<div class="pdf_generate">
<form action="<?=$_SERVER["PHP_SELF"]?>" method="post" >
    <input type="hidden" name="exhb" value="<?=$_REQUEST["exhb"]?>" />
    <p>Generate XLS from messages between:</p>
    
    <select name="company_from" id="company_from" class="company_select">
                                    <option value=""> -- Select -- </option>
                                    <? foreach ($companies as $company) {?>
                                        <option value="<?=urlencode($company)?>" <? if ($_REQUEST["company"]==urlencode($company)) {?>selected="selected"<?}?>><?=$company?></option>
                                    <?}?>
                                </select>
                                <select name="company_to" id="company_to" class="company_select">
                                    <option value=""> -- Select -- </option>
                                    <? foreach ($companies as $company) {?>
                                        <option value="<?=urlencode($company)?>" <? if ($_REQUEST["company"]==urlencode($company)) {?>selected="selected"<?}?>><?=$company?></option>
                                    <?}?>
                                </select>
                                <input type="submit" id="generate" name="go" value="Generate XLS" class="company_generate" />
    

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