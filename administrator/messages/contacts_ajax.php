<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $USER;
?>
<? if ($USER->isAdmin()) {?>
    
<?
/*
if (count($_REQUEST)>1) {
    foreach ($_REQUEST as $k=>$v) {
        $arr[] = $k;
    }
    
    $_REQUEST["comp"] = htmlspecialchars_decode(implode("&", $arr));
}

echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

echo 'rr'.htmlspecialchars_decode($_REQUEST["comp"]);*/
if ($_REQUEST["comp"]) {?>
<option value=""> -- Select -- </option>

<?
$filter = Array("WORK_COMPANY"=>(urldecode($_REQUEST["comp"])));
$rsUsers = CUser::GetList(($by="UF_FIO"), ($order="asc"), $filter, array("SELECT"=>array("UF_*")));
while ($arUsers = $rsUsers->GetNext()) {
?>
   <option value="<?=$arUsers["ID"]?>"><?=$arUsers["UF_FIO"]?></option> 
<?}}}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>