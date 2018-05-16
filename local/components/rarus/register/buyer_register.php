<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$data = &$arResult["POST_VALUES"];

if ($data["EMAIL"] != $data["CONF_EMAIL"]) {
    $arResult["ERRORS"][] = GetMessage("R_B_EMAIL_NOT_EQUAL");
}

if ($data["PASSWORD"] != $data["CONF_PASSWORD"]) {
    $arResult["ERRORS"][] = GetMessage("R_B_PASS_NOT_EQUAL");
}

if (empty($data["EXHIBITION"])) {
    $arResult["ERRORS"][] = GetMessage("R_B_EMPTY_EXHIBITION");
}

$morning = array();
$evening = array();
$exhibitionID = "";


/*получаем выбор пользователя на утро или на вечер*/
foreach ($data["EXHIBITION"] as $exhibID => $arSelectedData) {
    $exhibitionID = $exhibID;

    if (isset($arSelectedData["MORNING"]) && $arSelectedData["MORNING"]) {
        $morning = array($arSelectedData["MORNING"]);
    }

    if (isset($arSelectedData["EVENING"]) && $arSelectedData["EVENING"]) {
        $evening = array($arSelectedData["EVENING"]);
    }
}

$arExhibition = &$arResult["EXHIBITION"][$exhibitionID];

//получаем пароль и шифруем его для записи в пользовательское поле
$password = $data["PASSWORD"];
$passwordCoded = "";

if (strlen($password) <= 0) //если пароль не задан, генерируем его
{
    //Пароль рассчитан на 10! комбинаций / разных гостей
    $pasAr = array('d', 'p', '!', 'l', '9', '#', 'm', 'A', 'r', '2');
    shuffle($pasAr);
    $password = implode("", $pasAr);
}

//шифруем
$passwordCoded = makePassCode($password);

$login = $data["LOGIN"];
if (strlen($login) <= 0) //если логин не задан, генерируем его
{
    /*получаем транслит первого слова названия выставки*/
    $tok = strtok($arExhibition["CODE"], " -_");

    //добавляем первое слово с кода выставки, потом мыло, потом случайный набор цифр
    $loginAr = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
    shuffle($loginAr);
    $login = $tok . $data["EMAIL"] . implode("", $loginAr);
}

$webSite = preg_replace("/https?:\/\//", "", $data["WEB_SITE"]);
if (strlen($webSite) > 0) {
    $webSite = "http://" . $webSite;
}

/*Подготавливаем массив для создания пользователя*/

$bConfirmReq = COption::GetOptionString("main", "new_user_registration_email_confirmation", "N") == "Y";

$arUserFields = array(
    "ACTIVE" => $bConfirmReq ? "N" : "Y",
    "CONFIRM_CODE" => $bConfirmReq ? randString(8) : "",
    "LID" => SITE_ID,
    "USER_IP" => $_SERVER["REMOTE_ADDR"],
    "USER_HOST" => @gethostbyaddr($REMOTE_ADDR),
    "LOGIN" => $login,
    "PASSWORD" => $password,
    "CONFIRM_PASSWORD" => $password,
    "NAME" => $data["NAME"],
    "LAST_NAME" => $data["LAST_NAME"],
    "EMAIL" => $data["EMAIL"],
    "MAIL" => $data["EMAIL"],
    "PERSONAL_PHONE" => cutPhone($data["PHONE"]),
    "PERSONAL_MOBILE" => cutPhone($data["MOBILE_PHONE"]),
    "WORK_COMPANY" => $data["COMPANY_NAME"],
    "WORK_POSITION" => $data["JOB_POST"],
    "UF_PAS" => $passwordCoded,
    "UF_FIO" => $data["NAME"] . " " . $data["LAST_NAME"]
);

/*Добавляем стандартные группы при регистрации*/
$def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");

if ($def_group != "") {
    $arUserFields["GROUP_ID"] = explode(",", $def_group);
}

/*Добавляем в группу неподтвержденных гостей выставки*/
$ucGuestGroupID = $arExhibition["PROPERTIES"]["UC_GUESTS_GROUP"]["VALUE"];
if ($ucGuestGroupID) {
    $arUserFields["GROUP_ID"][] = $ucGuestGroupID;
}

/*Записываем результаты вебформы в свойства пользователя*/

$exhibPropName = CFormMatrix::getPropertyIDByExh($exhibitionID);
if ($exhibPropName && $RESULT_ID) {
    $arUserFields[$exhibPropName] = $RESULT_ID;
    $arUserFields["UF_ID_COMP"] = $RESULT_ID;
}

/*отправляем в компонент*/
$arResult["VALUES"] = $arUserFields;
$bOk = true;

global $USER_FIELD_MANAGER;
$USER_FIELD_MANAGER->EditFormAddFields("USER", $arResult["VALUES"]);

if ($bOk) {
    $user = new CUser();
    $USER_ID = $user->Add($arResult["VALUES"]);
}

if (intval($USER_ID) > 0) {
    $hlblock = HL\HighloadBlockTable::getById(intval($arParams["GUEST_COLLEAGUE_HL_BLOCK_ID"]))->fetch();
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    $colleaguesIds = [];
    $ufEnum = CUserTypeEntity::GetList( array(), array("ENTITY_ID" => "HLBLOCK_" . $arParams["GUEST_COLLEAGUE_HL_BLOCK_ID"], 'LANG' => LANGUAGE_ID, 'FIELD_NAME' => 'UF_DAYTIME') )->Fetch();
    foreach ($data["COLLEAGUE"] as $key=>$colleague) {
        if(array_filter($colleague)){ //Штатная работа array_filter без callback`а, удаляет все элементы из массива ранвые false, null, "" https://stackoverflow.com/questions/26455839/what-does-array-filter-with-no-callback-do
            $dayTime = CUserFieldEnum::GetList(array(), array(
                "USER_FIELD_ID" => $ufEnum['ID'],
                "XML_ID" => strtolower($key),
            ))->Fetch();
            $result = $entity_data_class::add([
                "UF_NAME" => $colleague["NAME"],
                "UF_SURNAME" => $colleague["LAST_NAME"],
                "UF_SALUTATION" => $colleague["SALUTATION"],
                "UF_JOB_TITLE" => $colleague["JOB_POST"],
                "UF_EMAIL" => $colleague["EMAIL"],
                "UF_DAYTIME" => [$dayTime['ID']],
            ]);
            $colleaguesIds[] = $result->getId();
        }
    }

    $arUserFormFields = array(
        "UF_USER_ID" => $USER_ID,
        "UF_COMPANY" => $data["COMPANY_NAME"],                        //название компании
        "UF_PRIORITY_AREAS" => $data["BUSINESS_TYPE"],    //вид деятельности
        "UF_ADDRESS" => $data["COMPANY_ADDRESS"],                    //фактический адрес компании
        "UF_POSTCODE" => $data["INDEX"],                                //индекс
        "UF_CITY" => $data["CITY"],                                //город
        "UF_COUNTRY" => $data["COUNTRY"],        //страна
        "UF_COUNTRY_OTHER" => $data["COUNTRY_OTHER"],        //страна
        "UF_NAME" => $data["NAME"],                                //имя
        "UF_SURNAME" => $data["LAST_NAME"],                            //фамилия
        "UF_SALUTATION" => $data["SALUTATION"],        //обращение
        "UF_POSITION" => $data["JOB_POST"],                            //должность
        "UF_PHONE" => cutPhone($data["PHONE"]),                    //телефон
        "UF_SKYPE" => $data["SKYPE"],                                //скайп
        "UF_EMAIL" => $data["EMAIL"],                                //email
        "UF_MOBILE" => cutPhone($data["MOBILE_PHONE"]),            //мобильный телефон
        "UF_SITE" => $webSite,                                    //веб сайт
        "UF_DESCRIPTION" => $data["COMPANY_DESCRIPTION"],            //описание компании

        /*Приоритетные направления*/
        "UF_NORTH_AMERICA" => getPriorityAreas($data["NORTH_AMERICA"]),    //North America
        "UF_EUROPE" => getPriorityAreas($data["EUROPE"]),            //Europe
        "UF_SOUTH_AMERICA" => getPriorityAreas($data["SOUTH_AMERICA"]),    //South America
        "UF_AFRICA" => getPriorityAreas($data["AFRICA"]),            //Africa
        "UF_ASIA" => getPriorityAreas($data["ASIA"]),            //Asia
        "UF_OCEANIA" => getPriorityAreas($data["OCEANIA"]),            //Oceania and Arctic and Antarctica

        /*логин пароль*/
        "UF_LOGIN" => $login,                                        //Введите логин/гостевое имя
        "UF_PASSWORD" => $password,                                //Введите пароль
        "UF_COLLEAGUES" => $colleaguesIds,

        /*утро или вечер*/
        "UF_MORNING" => $morning,                //Утро
        "UF_EVENING" => $evening,                //Вечер
        "UF_EXHIB_ID" => $exhibitionID,
    );

// Создаем запить с HL блоке
    $hlblock = HL\HighloadBlockTable::getById(intval($arParams["GUEST_HL_BLOCK_ID"]))->fetch();
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    $result = $entity_data_class::add($arUserFormFields);


    $register_done = true;

    // authorize user
    if ($arParams["AUTH"] == "Y" && $arResult["VALUES"]["ACTIVE"] == "Y") {
        if (!$arAuthResult = $USER->Login($arResult["VALUES"]["LOGIN"], $arResult["VALUES"]["PASSWORD"]))
            $arResult["ERRORS"][] = $arAuthResult;
    }

    $arResult['VALUES']["USER_ID"] = $USER_ID;

    $arEventFields = $arResult['VALUES'];
    //unset($arEventFields["PASSWORD"]);
    unset($arEventFields["CONFIRM_PASSWORD"]);

    $event = new CEvent;
    $eventName = array();

    /*Выбор типа почтового собырия*/
    if (!empty($morning)) {
        $eventName["M"] = [
            "EVENT" => $arExhibition["PROPERTIES"]["EVENT_REG_GUEST"]["VALUE"],
            "EXIB" => [
                "EXIB_NAME_RU" => $arExhibition["NAME"],
                "EXIB_NAME_EN" => $arExhibition["PROPERTIES"]["NAME_EN"]["VALUE"],
                "EXIB_SHORT_RU" => $arExhibition["PROPERTIES"]["V_RU"]["VALUE"],
                "EXIB_SHORT_EN" => $arExhibition["PROPERTIES"]["V_EN"]["VALUE"],
                "EXIB_DATE" => $arExhibition["PROPERTIES"]["DATE"]["VALUE"],
                "EXIB_PLACE" => $arExhibition["PROPERTIES"]["VENUE"]["VALUE"],
                "EVENT_EMAIL" => $arExhibition["PROPERTIES"]["EVENT_EMAIL"]["VALUE"],
                "EXIB_YEAR" => $arExhibition["PROPERTIES"]["menu_en"]["VALUE"],
                "TYPE" => "Рабочие встречи",
                "TYPE_KIEV" => "Дякуємо Вам за реєстрацію на Робочі зустрічі за заздалегідь призначенім розкладом на"
            ]
        ];
    }
    if (!empty($evening)) {
        $eventName["E"] = [
            "EVENT" => $arExhibition["PROPERTIES"]["EVENT_REG_GUEST"]["VALUE"],
            "EXIB" => [
                "EXIB_NAME_RU" => $arExhibition["NAME"],
                "EXIB_NAME_EN" => $arExhibition["PROPERTIES"]["NAME_EN"]["VALUE"],
                "EXIB_SHORT_RU" => $arExhibition["PROPERTIES"]["V_RU"]["VALUE"],
                "EXIB_SHORT_EN" => $arExhibition["PROPERTIES"]["V_EN"]["VALUE"],
                "EXIB_DATE" => $arExhibition["PROPERTIES"]["DATE"]["VALUE"],
                "EXIB_PLACE" => $arExhibition["PROPERTIES"]["VENUE"]["VALUE"],
                "EXIB_YEAR" => $arExhibition["PROPERTIES"]["menu_en"]["VALUE"],
                "EVENT_EMAIL" => $arExhibition["PROPERTIES"]["EVENT_EMAIL"]["VALUE"],
                "TYPE" => "Вечерний коктейль",
                "TYPE_KIEV" => "Дякуємо Вам за реєстрацію на Вечірній коктейль"
            ]
        ];
    }

    foreach ($eventName as $when => $eventInfo) {
        $fullEventFields = array_merge($arEventFields, $eventInfo["EXIB"]);
        $event->SendImmediate($eventInfo["EVENT"], SITE_ID, $fullEventFields);

        $eventColleagueRegistration = $arExhibition["PROPERTIES"]["EVENT_REG_GUEST_COLLEAGUE"]["VALUE"];

        if ($arParams["COLLEAGUE_SEND_EMAIL"] == "Y") {
            if ($when == "E") {
                foreach ($data["COLLEAGUE"] as $type => $arColleague) {
                    $arEveningColleagueEventFields = array();
                    if (!empty($arColleague["EMAIL"]) && !empty($arColleague["NAME"]) && "MORNING" !== $type) {
                        $arEveningColleagueEventFields = $arColleague;
                        $arEveningColleagueEventFields["MAIL"] = $arColleague["EMAIL"];
                        $arEveningColleagueEventFields["EXHIB"] = $arResult["EXHIBITION"][$exhibitionID]["PROPERTIES"]["menu_ru"]["VALUE"];
                        $arEveningColleagueEventFields["EXIB_SHORT_EN"] = $arResult["EXHIBITION"][$exhibitionID]["PROPERTIES"]["V_EN"]["VALUE"];
                        $arEveningColleagueEventFields["BUYER"] = "{$data["NAME"]} {$data["LAST_NAME"]}";

                        $event->Send($eventColleagueRegistration, SITE_ID, $arEveningColleagueEventFields);
                    }
                }
            } elseif ($when == "M") {
                $arMailFields = array();
                if (!empty($data["COLLEAGUE"]["MORNING"]["EMAIL"]) && !empty($data["COLLEAGUE"]["MORNING"]["EMAIL"])) {
                    $arMailFields = $data["COLLEAGUE"]["MORNING"];
                    $arMailFields["MAIL"] = $data["COLLEAGUE"]["MORNING"]["EMAIL"];
                    $arMailFields["EXHIB"] = $arResult["EXHIBITION"][$exhibitionID]["PROPERTIES"]["menu_ru"]["VALUE"];
                    $arMailFields["EXIB_SHORT_EN"] = $arResult["EXHIBITION"][$exhibitionID]["PROPERTIES"]["V_EN"]["VALUE"];
                    $arMailFields["BUYER"] = "{$data["NAME"]} {$data["LAST_NAME"]}";

                    $event->Send($eventColleagueRegistration, SITE_ID, $arMailFields);
                }
            }
        }
    }

    if ($bConfirmReq) {
        $event->SendImmediate("NEW_USER_CONFIRM", SITE_ID, $arEventFields);
    }
} else {
    $arResult["ERRORS"][] = $user->LAST_ERROR;
}

if (count($arResult["ERRORS"]) <= 0) {
    if (COption::GetOptionString("main", "event_log_register", "N") === "Y")
        CEventLog::Log("SECURITY", "USER_REGISTER", "main", $ID);
} else {
    if (COption::GetOptionString("main", "event_log_register_fail", "N") === "Y")
        CEventLog::Log("SECURITY", "USER_REGISTER_FAIL", "main", $ID, implode("<br>", $arResult["ERRORS"]));
}
?>