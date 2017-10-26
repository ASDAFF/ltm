<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Хранилище гостей");
?>
<?$APPLICATION->IncludeComponent(
	"rarus:admin.guest.storage", 
	".default", 
	array(
		"COUNT" => "30",
		"COMPONENT_TEMPLATE" => ".default",
		"FIELDS" => array(
			0 => "612",
			//1 => "613",
			//2 => "615",
			3 => "616",
			4 => "617",
			5 => "619",
			6 => "620",
			7 => "622",
			//8 => "624",
			9 => "625",
			10 => "643",
			/*11 => "644",
			12 => "645",
			13 => "646",
			14 => "647",
			15 => "648",
			16 => "649",*/
			17 => 'UF_FULL_ADDRESS',
			18 => 'MORNING_COLLEAGUE_NAME',
			19 => 'MORNING_COLLEAGUE_EMAIL',
			20 => 'MORNING_COLLEAGUE_JOB_TITLE',
			21 => 'EVENING_COLLEAGUE_NAME',
			22 => 'EVENING_COLLEAGUE_EMAIL',
			23 => 'EVENING_COLLEAGUE_JOB_TITLE',
		)
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>