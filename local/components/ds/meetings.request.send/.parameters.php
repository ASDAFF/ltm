<?
if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentParameters = array(
    "GROUPS"     => array(),
    "PARAMETERS" => array(
        "APP_ID"               => array(
            "PARENT"  => "BASE",
            "NAME"    => GetMessage("APP_ID"),
            "TYPE"    => "STRING",
            "DEFAULT" => '={$_REQUEST["APP_ID"]}',
        ),
        "EXHIBITION_IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME"   => GetMessage("EXHIBITION_IBLOCK_ID"),
            "TYPE"   => "STRING",
        ),
        "IS_HB"                => array(
            "PARENT" => "BASE",
            "NAME"   => GetMessage("IS_HB"),
            "TYPE"   => "CHECKBOX",
        ),
    ),
);
?>