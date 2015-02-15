<?
$APPLICATION->IncludeComponent(
	"rarus:messages.new",
	"",
	Array(
		"HLID" => "2",
		"EID" => $exhibID,
		"COPY_TO_OUTBOX" => "N",
		"SEND_EMAIL" => "N",
		"MID" => $_REQUEST["mes"],
	    "UID" => $_REQUEST["id"],
	    "SET_TITLE" => "N",
    	"URL_TEMPLATES_HLM_LIST" => "/cabinet/messages/#FCODE#/",
		"URL_TEMPLATES_HLM_READ" => "/cabinet/service/_read.php?MID=#MID#",
		"URL_TEMPLATES_HLM_NEW" => "/cabinet/service/_write.php?id=#UID#",
		"URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",
	    "GROUP_WRITE" => $exhibPGroup,
	    //"GROUP_TYPE" => "GUEST",

	),
false
);?>
