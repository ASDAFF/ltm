<?
class Chandlers
{
	function OnBeforeEventAddHandler($event, $lid, $arFields)
	{
	    if ($event == "TEST_NEW_SEND")
	    {
	        require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface/lib/mail_attach.php");
	        SendAttache($event, $lid, $arFields, "/readme.html");
	        $event = 'null'; $lid = 'null';
	    }
	}


	function OnAfterUserLogoutHandler($arParams)
	{
	    unset($_SESSION["USER_TYPE"]);
	}

	function onBeforeResultAddHandlers($WEB_FORM_ID, $arFields, $arrVALUES)
	{
	    //пишем логи по подтверждению
	    $file = $_SERVER["DOCUMENT_ROOT"] . "/upload/webform.log";

	    file_put_contents($file, "Дата:" . date("d.m.Y H:m:s") . "\r\n", FILE_APPEND);
	    file_put_contents($file, "WEB_FORM_ID:" . $WEB_FORM_ID . "\r\n", FILE_APPEND);
	    file_put_contents($file, "arFields:\r\n" . print_r($arFields,true). "\r\n", FILE_APPEND);
	    file_put_contents($file, "arrVALUES:\r\n" . print_r($arrVALUES,true). "\r\n", FILE_APPEND);

	}
}

?>