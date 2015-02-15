<?php
/**
 * Created by IntelliJ IDEA.
 * User: dimich
 * Date: 28/02/14
 * Time: 20:11
 */
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/forum/include.php");

global $USER;
if(!is_object($USER))
    $USER = new CUser;

$result = array();
$result['status'] = false;
$result['message'] = '';

switch($mode)
{
    case 'login':
        if(!$USER->IsAuthorized())
        {
            $res = $USER->Login(htmlspecialcharsbx($_POST['login']), htmlspecialcharsbx($_POST['password']), 'Y');
            if(empty($res['MESSAGE']))
                $result['status'] = true;
            else
                $result['message'] = strip_tags($res['MESSAGE']);
        } else {
            $result['status'] = true;
            $result['message'] = 'Пользователь уже авторизован';

        }

        break;

    case 'register':
        if(!$USER->IsAuthorized())
        {
            $res = $USER->SimpleRegister(htmlspecialcharsbx($_POST['email']));
            if($res['TYPE'] == 'OK')
                $result['status'] = true;
            else
                $result['message'] = strip_tags($res['MESSAGE']);
        }
        break;

    case 'recovery':
        if(!$USER->IsAuthorized())
        {
            $emailTo = htmlspecialcharsbx($_POST['email']);
            $filter = Array("ACTIVE" => "Y",
                "EMAIL"  => $emailTo);
            $user = CUser::GetList(($by="timestamp_x"), ($order="desc"), $filter)->Fetch();
            if(count($user) > 1)
            {
                $password = mb_substr(md5(uniqid(rand(),true)), 0, 8);

                $USER->Update($user['ID'], Array("PASSWORD" => $password, "CONFIRM_PASSWORD" => $password));
                $USER->SendUserInfo($user['ID'], SITE_ID, "Пароль изменён: ". $password);

                $rsSites = CSite::GetByID("s1");
                $arSite = $rsSites->Fetch();

                $arEventFields = array(
                    "SITE_NAME"           => $arSite['NAME'],
                    "EMAIL"               => $emailTo,
                    "MESSAGE"             => 'Пароль успешно восстановлен. Новый пароль: '. $password,
                    "USER_ID"             => $user['ID'],
                    "LOGIN"               => $user['LOGIN'],
                );
                CEvent::Send('USER_PASS_RECOVERY', $arSite, $arEventFields, "N");
                //bxmail('example@mail.ru', 'Восстановление пароля', 'Пароль восстановлен: '. $password);

                $result['message'] = "Пароль успешно изменён.";
            }
            else
                $result['message'] = 'Пользователь с такие e-mail адресом не найден.';
        }
        break;

    case 'exit':
        if($USER->IsAuthorized())
        {
            $USER->logout();
            $result['status'] = true;
        }
        break;
}

echo json_encode($result);
