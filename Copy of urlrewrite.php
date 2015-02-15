<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/morning/schedule/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/morning/schedule/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/edit/colleague/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/edit/colleague.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/guest/matrix/.*.*.*.*.*.*.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/guest/matrix/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/edit/company/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/edit/participant-company.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/morning/list/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/morning/list/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/edit/profile/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/edit/profile.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/deadline/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/deadline/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/morning/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/morning/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/evening/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/evening/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/participant/matrix/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/participant/matrix/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/catalog/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/catalog/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/participant/spam/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/participant/spam/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/participant/off/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/participant/off/index.php",
	),
	array(
		"CONDITION" => "#^/members/([0-9]+)/.*.*.*.*.*.*.*#",
		"RULE" => "ID=\$1",
		"ID" => "",
		"PATH" => "/members/detail.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/participant/on/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/participant/on/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/guest/([^/]+)/.*#",
		"RULE" => "EXHIBIT_CODE=\$1&ACT=\$2",
		"ID" => "",
		"PATH" => "/admin/guest/guest-list.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/hb/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/hb/index.php",
	),
	array(
		"CONDITION" => "#^/members/([0-9a-zA-Z-_]+)/.*#",
		"RULE" => "CODE=\$1",
		"ID" => "",
		"PATH" => "/members/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/profile.php",
	),
	array(
		"CONDITION" => "#^/organizers/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/organizers/index.php",
	),
	array(
		"CONDITION" => "#^/partners/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/partners/index.php",
	),
	array(
		"CONDITION" => "#^/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/news/index.php",
	),
);

?>