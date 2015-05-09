<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//Добавить в параметры FORM_ID


$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/admin/";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/admin/login.php";
}

if(strLen($arParams["USER"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Пользователю!<br />";
}

if(!($USER->IsAuthorized()))
{
	$arResult["ERROR_MESSAGE"] = "Вы не авторизованы!<br />";
}

if(strLen($arParams["FORM_ID"])<=0){
	$arParams["FORM_ID"] = '1';
}


if($arResult["ERROR_MESSAGE"] == '')
{

	if($USER->IsAdmin())
	{
	    if(isset($_REQUEST["pass_save"]))
	    {
	        $user = new CUser;
	        $strError = '';
	        if(isset($_REQUEST["password"]) && strlen(trim($_REQUEST["password"])) > 0)
	        {
				$pass = trim($_REQUEST["password"]);
	            $fields = array(
	                "ADMIN_NOTES"      => $pass,
	                "PASSWORD"       => $pass,
	                "CONFIRM_PASSWORD" => $pass,
	                "UF_PAS" => base64_encode(str_code($pass, "luxoran"))
	            );
	            $user->Update($arParams["USER"], $fields);
	            $strError .= $user->LAST_ERROR;
	        }
	        else
	        {
	            $strError .= "Вы не ввели пароль!<br />";
	        }

	        if(!$strError)
	        {
	            $arResult["MESSAGE"] = GetMessage("ADMIN_USER_PASS");
	        }
	        else
	        {
	            $arResult["MESSAGE"] = $strError;
	        }
	    }

	    $rsUser = $USER->GetByID($arParams["USER"]);
	    if($arUser = $rsUser->Fetch())
	    {
	        $arResult["ID"] = $arUser["ID"];
	        $arResult["LOGIN"] = $arUser["LOGIN"];
	        $arResult["EMAIL"] = $arUser["EMAIL"];
	    	$arResult["NAME"] = $arUser["NAME"];
	    	$arResult["LAST_NAME"] = $arUser["LAST_NAME"];
	    	$arResult["FIO"] = $arUser["UF_FIO"];
	    	$arResult["WORK_COMPANY"] = $arUser["WORK_COMPANY"];
	    	if(strlen($arUser["UF_PAS"]) > 0)
	    	{
	    	    $arResult["PASSWORD"] = str_code(base64_decode($arUser["UF_PAS"]), "luxoran");
	    	}
	    }
	    else
	    {
	        $arResult["ERROR_MESSAGE"] = "Пользовател с ID = {$arParams["USER"]} не найден!";
	    }
	}
	else
	{
		$arResult["ERROR_MESSAGE"] = "У вас недостаточно прав для просмотра данной страницы!";
	}
}

//echo "<pre>"; print_r($_REQUEST); echo "</pre>";

$this->IncludeComponentTemplate();
?>