<?
$APPLICATION->IncludeComponent(
	"rarus:messages.list",
	"",
	Array(
		"HLID" => "2",
		"EID" => $exhibID,
		"FID" => "3",
	    "SET_TITLE" => "N",
		"PM_PER_PAGE" => "20",
		"DATE_FORMAT" => "d.m.Y",
		"DATE_TIME_FORMAT" => "H:m:i",
        "URL_TEMPLATES_HLM_LIST" => "/cabinet/messages/.$exhibCode./#FCODE#/",
        "URL_TEMPLATES_HLM_READ" => "/cabinet/service/_read.php?MID=#MID#",
        "URL_TEMPLATES_HLM_NEW" => "/cabinet/service/_write.php?id=#UID#",
        "URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",

	),
false
);?>
