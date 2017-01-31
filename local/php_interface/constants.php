<?php
define("PARTICIPANT_CABINET", "N");
define("GUEST_CABINET", "N");

define("IMG_TMP_PATH", "/upload/tmp/");
define("PHOTO_GALLERY_ID", 16);

define("GUEST_FORM_ID", 10);
define("PARTICIPANT_FORM_ID", 3);

//Путь, куда сохраняются фотографии из формы регистрации участника
define('PATH_PHOTOS', $_SERVER["DOCUMENT_ROOT"].'/upload/ajax_photo/photos/');
//Путь, куда сохраняется логотип из формы регистрации участника
define('PATH_LOGO', $_SERVER["DOCUMENT_ROOT"].'/upload/ajax_photo/logotip/');
//Путь, куда сохраняется персональное фото из формы регистрации участника
define('PATH_PERS_PHOTO', $_SERVER["DOCUMENT_ROOT"].'/upload/ajax_photo/photo_pers/');

//Форма представителя
//ID инфоблока фотогалереи
define('IBLOCK_PHOTO', 16);
//ID формы представителей
define('ID_FROM_COMP', 3);
//ID формы Участники Представители Москва Весна (по умолчанию)
define('ID_FROM_COMP_DEFAULT', 4);
//Типы событий (из инфоблока "Выставки" с id = 15)
//Баку
define('ID_TYPE_1', 357);
//Москва осень
define('ID_TYPE_2', 358);
//Алмаата
define('ID_TYPE_3', 359);
//Киев
define('ID_TYPE_4', 360);
//Москва весна
define('ID_TYPE_5', 361);
//Москва весна - 2015
define('ID_TYPE_6', 488);
//Москва осень 2015
define('ID_MO15', 3523);
//Киев 2015
define('ID_KIEV15', 3522);
//Алмаата 2015
define('ID_ALM15', 3521);

//id групп пользователей (неподтв.)
//Баку
define('GROUP_1', 11);
//Москва осень
define('GROUP_2', 17);
//Алмаата
define('GROUP_3', 15);
//Киев
define('GROUP_4', 13);
//Москва весна
define('GROUP_5', 9);
//Москва весна - 2015
define('GROUP_6', 20);
//Москва осень 2015
define('GROUP_MO15', 51);
//Алмаата 2015
define('GROUP_ALM15', 43);
//Киев 2015
define('GROUP_KIEV15', 47);

//Форма гостя
//ID формы гостей
define('ID_FORM_GUEST', 10);?>