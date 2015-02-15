<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$arResult["ERROR_MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
    $arParams["PATH_TO_KAB"] = "/admin/";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
    $arParams["AUTH_PAGE"] = "/admin/login.php";
}

if(strLen($arParams["EXHIB_CODE"])<=0){
    $arResult["ERROR_MESSAGE"] = "Не введены данные по Выставке!<br />";
}

if(!($USER->IsAuthorized()))
{
    $arResult["ERROR_MESSAGE"] = "Вы не авторизованы!<br />";
}

if(!($USER->IsAdmin()))
{
    $arResult["ERROR_MESSAGE"] = "Вы не администратор!<br />";
}

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form"))
{
    $arResult["ERROR_MESSAGE"] = "Ошибка подключения модулей!<br />";
}

$arResult["URL"] = $APPLICATION->GetCurPage();

if($arResult["ERROR_MESSAGE"] == '')
{

    //получение данных выставки

    $arFilter = array(
        "CODE" => $arParams["EXHIB_CODE"],
        "ACTIVE" => "Y",
        "IBLOCK_ID" => $arParams["IBLOCK_ID"]
    );

    $arSelect = array(
        "ID",
        "NAME",
        "CODE",
        "IBLOCK_ID",
        "PROPERTY_*",
    );

    $rsExhib = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

    if($obExhib = $rsExhib->GetNextElement())
    {
        $arExhib = $obExhib->GetFields();
        $arExhib["PROPERTIES"] = $obExhib->GetProperties();

        $CPGroupID = $arExhib["PROPERTIES"]["USER_GROUP_ID"]["VALUE"];
        $UCPGroupID = $arExhib["PROPERTIES"]["UC_PARTICIPANTS_GROUP"]["VALUE"];
        $CGGroupID = $arExhib["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"];
        $UCGGroupID = $arExhib["PROPERTIES"]["UC_GUESTS_GROUP"]["VALUE"];

        //получение списка пользователей на выставку

        $arResult["PARTICIPANT"] = CGroup::GetGroupUser($CPGroupID);
        $arResult["GUEST"] = CGroup::GetGroupUser($CGGroupID);

        //получение пользователей
        $arUserFilter = array(
            "ID" => implode("|", array_merge($arResult["PARTICIPANT"], $arResult["GUEST"])),
            "ACTIVE" => "Y",
        );
        $arUserParameters = array(
        	"FIELDS" => array("ID", "LOGIN", "NAME", "LAST_NAME", "WORK_COMPANY", "EMAIL"),
            "SELECT" => array("UF_*")
        );

        $propName = CFormMatrix::getPropertyIDByExh($arExhib["ID"]);
        //$arUserFormAnswersId = array();

        $rsUsers = $USER->GetList(($by = "company_name"), ($order = "asc"), $arUserFilter, $arUserParameters);
        while($arUser = $rsUsers->Fetch())
        {
            $arResult["USERS"][$arUser["ID"]] = $arUser;
            //$arUserFormAnswersId[] = $arUser[$propName];
        }

        $arUsers = $arResult["USERS"];

/* Получение результатов вебформы на будущее, если понадобится
        $arAnswersFilter = array("RESULT_ID"=>implode("|", $arUserFormAnswersId));

        $arAnswers = array();
        CForm::GetResultAnswerArray(
        10,
        $arResult["GUEST_FORM_QUESTIONS"],
        $arAnswers,
        ($b = false),
        $arAnswersFilter);
*/

        //разделение гостей по форме участия
        foreach ($arResult["GUEST"] as $guestID)
        {
            //$resultID = $arUsers[$guestID][$propName];
            //$answer = $arAnswers[$resultID];

        	if($arUsers[$guestID]["UF_MR"])
        	{
        		$arResult["GUEST_M"][$guestID] = $arUsers[$guestID];
        	}
        	if($arUsers[$guestID]["UF_EV"])
        	{
        	    $arResult["GUEST_E"][$guestID] = $arUsers[$guestID];
        	}
        	if($arUsers[$guestID]["UF_HB"])
        	{
        	    $arResult["GUEST_HB"][$guestID] = $arUsers[$guestID];
        	}
        }

        $arResult["PARTICIPANT"] = array_flip($arResult["PARTICIPANT"]);


        foreach ($arResult["PARTICIPANT"] as $partID => &$value)
        {
            $value = $arUsers[$partID];
        }

        //Сортировка пользователей по названию компании
        $arSortKey = array("PARTICIPANT", "GUEST_M", "GUEST_E", "GUEST_HB");

        foreach ($arSortKey as $key)
        {
            usort($arResult[$key],
            function ($a, $b)
            {
                $wc_a = $a["WORK_COMPANY"];
                $wc_b = $b["WORK_COMPANY"];

                if ($wc_a == $wc_b) {
                    return 0;
                }
                return ($wc_a < $wc_b) ? -1 : 1;
            }
            );
        }

    }
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]))//обработка поста
{
    $arResult["SUBJECT"] = $subject = htmlspecialcharsEx($_POST["subj"]);
    $arResult["MESSAGE_TEXT"] = $message = htmlspecialcharsEx($_POST["message_text"]);

    $arResult["MESSAGE"] = '';

    if($subject == '')
    {
        $arResult["MESSAGE"] = "Вы не ввели Тему<br />";
    }
    if($message == '')
    {
        $arResult["MESSAGE"] .= "Вы не ввели Текст сообщения<br />";
    }

    if($arResult["MESSAGE"] == '')
    {
        $sendedUserID = array();

        if(isset($_POST["GUEST_M"]) && $_POST["GUEST_M"] != "")
        {
            foreach ($arResult["GUEST_M"] as $arUser)
            {
                if(!in_array($arUser["ID"], $sendedUserID))
                {
                    sendMessage($arUser["ID"], $subject, $message, $arUsers, $arResult);
                    $sendedUserID[] = $arUser["ID"];
                }
            }
        }

        if(isset($_POST["GUEST_HB"]) && $_POST["GUEST_HB"] != "")
        {
            foreach ($arResult["GUEST_HB"] as $arUser)
            {
                if(!in_array($arUser["ID"], $sendedUserID))
                {
                    sendMessage($arUser["ID"], $subject, $message, $arUsers, $arResult);
                    $sendedUserID[] = $arUser["ID"];
                }
            }
        }

        if(isset($_POST["PARTICIPANT"]) && $_POST["PARTICIPANT"] != "")
        {
            foreach ($arResult["PARTICIPANT"] as $arUser)
            {
                if(!in_array($arUser["ID"], $sendedUserID))
                {
                    sendMessage($arUser["ID"], $subject, $message, $arUsers, $arResult);
                    $sendedUserID[] = $arUser["ID"];
                }
            }
        }


        //отправка просто выделенным пользователям
        if(isset($_POST["UIDS"]) && !empty($_POST["UIDS"]))
        {
            foreach ($_POST["UIDS"] as $UID => $val)
            {
                if(!in_array($UID, $sendedUserID))
                {
                    sendMessage($UID, $subject, $message, $arUsers, $arResult);
                    $sendedUserID[] = $UID;
                }
            }
        }
    }
}


$this->IncludeComponentTemplate();

function sendMessage($UID, $subject, $message, &$arUsers, &$arResult)
{
    CModule::IncludeModule("forum");

    $arFields = Array(
        "AUTHOR_ID"    => 1,
        "POST_SUBJ"    => $subject,
        "POST_MESSAGE" => $message,
        "USER_ID"      => $UID,
        "COPY_TO_OUTBOX" => ""
    );


    $ID = CForumPrivateMessage::Send($arFields);

    if (IntVal($ID)<=0)
    {
        $arResult["MESSAGE"] .= "Не отправилось Сообщение Пользователю ".$UID."<br />";
	}
	else
	{
	    $arResult["MESSAGE"] .= "Сообщение отправлено<br />";

    	$arFieldsMes = array();
    	$arFieldsMes["TO_EMAIL"] = $arUsers[$UID]['EMAIL'];
    	$arFieldsMes["SUBJECT"] = $subject;
    	$arFieldsMes["MESSAGE"] = $message;
    	$arFieldsMes["MESSAGE_DATE"] = date("d.m.Y");

    	CEvent::Send("NEW_ADMIN_MESSAGE", "s1", $arFieldsMes );
	}
}

?>
