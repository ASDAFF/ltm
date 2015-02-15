<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("My registration info");
	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
?><?$APPLICATION->IncludeComponent(
	"btm:form.result.view.user",
	"guest",
	Array(
		"SEF_MODE" => "Y",
		"RESULT_ID" => $arUser["UF_ANKETA"],
		"SHOW_ADDITIONAL" => "Y",
		"SHOW_ANSWER_VALUE" => "Y",
		"SHOW_STATUS" => "Y",
		"EDIT_URL" => "/ru/personal/info/my/edit.php",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
        "IS_ACTIVE" => "N"
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>