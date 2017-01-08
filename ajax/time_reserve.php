<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
if(isset($_REQUEST["app"]) && $_REQUEST["app"]!=''){
  $appId = $_REQUEST["app"];
}
else{
  $appId=1;
}
?>
<?$APPLICATION->IncludeComponent(
  "doka:meetings.time.reserve",
  "",
  Array(
    "APP_ID" => $appId,
    "TIME" => $_REQUEST['time']
  ),
  false
);?>