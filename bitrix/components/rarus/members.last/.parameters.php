<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arUserGroups = array();

$rsUserGroup = CGroup::GetList(($by="c_sort"), ($order="desc"), array("ACTIVE" => "Y"),"Y");
while($arUserGroup = $rsUserGroup->Fetch())
{
    $arUserGroups[$arUserGroup["ID"]] = $arUserGroup["NAME"];
}

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS"  =>  array(
	    "USER_GROUP_ID" => array(
	        "PARENT" => "BASE",
	        "NAME" => GetMessage("T_IBLOCK_DESC_USER_GROUPS"),
	        "TYPE" => "LIST",
	        "DEFAULT" => "",
	        "VALUES" => $arUserGroups,
	        "ADDITIONAL_VALUES" => "N",
	        "MULTIPLE" => "Y",
	    ),
	    "FORM_FIELD_COMPANY_NAME_ID" => array(
	        "PARENT" => "BASE",
			"NAME" => GetMessage("MEMB_COMPANY_NAME_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "17",
	    ),
	    "FORM_FIELD_LOGIN_ID" => array(
	        "PARENT" => "BASE",
	        "NAME" => GetMessage("MEMB_LOGIN_ID"),
	        "TYPE" => "STRING",
	        "DEFAULT" => "18",
	    ),
		"ELEMENT_COUNT"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_CONT"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		),
	    "URL_TEMPLATE"  =>  Array(
	        "PARENT" => "BASE",
	        "NAME" => GetMessage("MEMB_URL_TEMPLATE"),
	        "TYPE" => "STRING",
	        "DEFAULT" => "/members/#ELEMENT_ID#/",
	    ),
		"CACHE_TIME"  =>  Array("DEFAULT"=>300),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);
?>
