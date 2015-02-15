<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Personal page");
$APPLICATION->SetTitle("Personal page");?>
	<? $APPLICATION->IncludeComponent(
        "btm:auth.form.ltm",
        "",
        Array(
            "REGISTER_URL" => "",
            "GUEST_ID" => "6",
            "PARTICIP_ID" => "4",
            "GUEST_URL" => "/ru/personal/",
            "PARTICIP_URL" => "/personal/",
            "SHOW_ERRORS" => "N",
            "IS_REDIRECT" => "Y",
            "IS_SHOW" => "N"
        ),
    false
    );?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>