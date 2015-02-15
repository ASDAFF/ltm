<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Редактировать");
?>
<?/*$APPLICATION->IncludeComponent(
	"btm:form.result.edit",
	"",
	Array(
		"SEF_MODE" => "Y",
		"RESULT_ID" => $_REQUEST["result"],
		"EDIT_ADDITIONAL" => "N",
		"EDIT_STATUS" => "N",
		"LIST_URL" => "result_list.php",
		"VIEW_URL" => "result_view.php",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "Y",
		"USE_EXTENDED_ERRORS" => "N",
		"SEF_FOLDER" => "/admin/service/",
		"SEF_URL_TEMPLATES" => Array(
			"edit" => "#RESULT_ID#/"
		),
		"VARIABLE_ALIASES" => Array(
			"edit" => Array(),
		)
	),
false
);*/?>

<?$APPLICATION->IncludeComponent(
	"rarus:form.result.edit",
	"",
	Array(
		"SEF_MODE" => "Y",
		"RESULT_ID" => $_REQUEST["result"],
		"EDIT_ADDITIONAL" => "N",
		"EDIT_STATUS" => "N",
		"LIST_URL" => "result_list.php",
		"VIEW_URL" => "result_view.php",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "Y",
		"USE_EXTENDED_ERRORS" => "N",
		"SEF_FOLDER" => "/admin/service/",
		"SEF_URL_TEMPLATES" => Array(
			"edit" => "#RESULT_ID#/"
		),
		"VARIABLE_ALIASES" => Array(
			"edit" => Array(),
		)
	),
false
);?>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>