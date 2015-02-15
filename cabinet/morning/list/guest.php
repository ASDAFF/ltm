<? 

$exhibCode = htmlspecialchars(trim($_REQUEST["EXHIBIT_CODE"]));

$APPLICATION->IncludeComponent("rarus:session.participant", "",
	Array(
			"EXHIB_IBLOCK_ID" => "15",
			"CACHE_TIME" => 3600,
			"EXHIB_CODE" => $exhibCode,
	),
	false
);
?>