<? 

$exhibCode = htmlspecialchars(trim($_REQUEST["EXHIBIT_CODE"]));


$APPLICATION->IncludeComponent("rarus:session.guest", "",
	Array(
			"EXHIB_IBLOCK_ID" => "15",
			"CACHE_TIME" => 600,
			"EXHIB_CODE" => $exhibCode,
			"TYPE" => "MORNING"
	),
	false
);
?>