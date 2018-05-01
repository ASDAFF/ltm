<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$page_start = microtime();

CModule::IncludeModule('form');
CModule::IncludeModule('iblock');

$_REQUEST["exhb"]=361;
BXClearCache(true, "/rst/");
define("BX_COMP_MANAGED_CACHE", false);

$arSelect = Array("ID", "NAME", "PROPERTY_USER_GROUP_ID", "PROPERTY_UC_PARTICIPANTS_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP");
$arFilter = Array("IBLOCK_ID"=>15, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$_REQUEST["exhb"]);
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    define('APP_EXHIBITOR', $arFields["PROPERTY_USER_GROUP_ID_VALUE"]);
    define('APP_BUYER', $arFields["PROPERTY_C_GUESTS_GROUP_VALUE"]);
}

define('MAIN_SITE_URL_PATH','luxurytravelmart.ru');
define('SITE_URL_PATH','app.luxurytravelmart.ru');

$cnt=0;
$arFilter = array("GROUPS_ID"=>array(APP_EXHIBITOR,APP_BUYER));
$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter, array("NAV_PARAMS"=>array("nPageSize"=>1000000,"iNumPage"=>0), "SELECT"=>array("UF_*")));
//$rsUsers->NavStart($limit);
while($arUsers = $rsUsers->getNext()) {
    $is_fav = '';
    $WORK_COUNTRY = $WORK_CITY = $ABOUT = $WORK_POSITION = $fields = $PERSONAL_PHOTO = '';

    $arrFRMSid = array($arUsers["UF_MSCSPRING2016"], $arUsers["UF_ID"], $arUsers["UF_ID5"], $arUsers["UF_ID6"], $arUsers["UF_ID_COMP"], $arUsers["UF_ID2"], $arUsers["UF_ID3"], $arUsers["UF_ID4"], $arUsers["UF_ID7"], $arUsers["UF_ID8"], $arUsers["UF_ID12"], $arUsers["UF_ID11"], $arUsers["UF_ID10"], $arUsers["UF_ID_GROUP"]);
    unset($pers_foto);
    unset($arrrole);

    $role = '';
    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
    foreach ($arGroups as $grp) {
        if ($grp==APP_BUYER) {
            $arrrole[] = 'Buyer';


            foreach ($arrFRMSid as $frm_id) {
                $arAnswer = CFormResult::GetDataByID($frm_id, array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391", "SIMPLE_QUESTION_575", "SIMPLE_QUESTION_772",));


                if ($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"] > 0) {

                    unset($rsFile);
                    unset($arFile);
                    $rsFile = CFile::GetByID($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"]);
                    $arFile = $rsFile->Fetch();
                    
                    $file = "http://" . SITE_URL_PATH . "/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]). "&w=242&h=242&zc=4";
                    }
                    $file = "http://" . MAIN_SITE_URL_PATH . "/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . MAIN_SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]). "&w=242&h=242&zc=4";
                    }

                } elseif ($arAnswer["SIMPLE_QUESTION_575"][0]["USER_FILE_ID"] > 0) {

                    unset($rsFile);
                    unset($arFile);
                    $rsFile = CFile::GetByID($arAnswer["SIMPLE_QUESTION_575"][0]["USER_FILE_ID"]);
                    $arFile = $rsFile->Fetch();

                    $file = "http://" . SITE_URL_PATH . "/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                    }
                    $file = "http://" . MAIN_SITE_URL_PATH . "/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . MAIN_SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                    }
                    //$pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                } elseif ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"] > 0) {

                    unset($rsFile);
                    unset($arFile);
                    $rsFile = CFile::GetByID($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]);
                    $arFile = $rsFile->Fetch();

                    $file = "http://" . SITE_URL_PATH . "/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                    }
                    $file = "http://" . MAIN_SITE_URL_PATH . "/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . MAIN_SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                    }
                    //$pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                }

                if ($arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"]) $WORK_COUNTRY = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                if ($arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"]) $WORK_CITY = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                if ($arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"]) $ABOUT = str_replace(array("'",'"',"&quot;"),"",$arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"]);
                if ($arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"]) $WORK_POSITION = str_replace(array("'",'"',"&quot;"),"",$arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"]);

            }

            if (!$pers_foto) $pers_foto = 'http://'.SITE_URL_PATH.'/image.php?src=/nopic.jpg&w=242&h=242&zc=4';
            $PERSONAL_PHOTO = $pers_foto;


        }
        if ($grp==APP_EXHIBITOR) {
            $arrrole[] = 'Exhibitor';


            foreach ($arrFRMSid as $frm_id) {
                $arAnswer = CFormResult::GetDataByID($frm_id, array("SIMPLE_QUESTION_772", "SIMPLE_QUESTION_652", "SIMPLE_QUESTION_269", "SIMPLE_QUESTION_575",));
                //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];



                if ($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"] > 0) {

                    unset($rsFile);
                    unset($arFile);
                    $rsFile = CFile::GetByID($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"]);
                    $arFile = $rsFile->Fetch();

                    $file = "http://" . SITE_URL_PATH . "/upload/" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=/upload/" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"]) . "&w=242&h=242&zc=4";
                    }
                    $file = "http://" . MAIN_SITE_URL_PATH . "/upload/" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . MAIN_SITE_URL_PATH . "/image.php?src=/upload/" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"]) . "&w=242&h=242&zc=4";
                    }

                } elseif ($arAnswer["SIMPLE_QUESTION_575"][0]["USER_FILE_ID"] > 0) {

                    unset($rsFile);
                    unset($arFile);
                    $rsFile = CFile::GetByID($arAnswer["SIMPLE_QUESTION_575"][0]["USER_FILE_ID"]);
                    $arFile = $rsFile->Fetch();

                    $file = "http://" . SITE_URL_PATH . "/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                    }
                    $file = "http://" . MAIN_SITE_URL_PATH . "/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . MAIN_SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                    }
                    //$pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                } elseif ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"] > 0) {

                    unset($rsFile);
                    unset($arFile);
                    $rsFile = CFile::GetByID($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]);
                    $arFile = $rsFile->Fetch();

                    $file = "http://" . SITE_URL_PATH . "/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                    }
                    $file = "http://" . MAIN_SITE_URL_PATH . "/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]);
                    $file_headers = get_headers($file);
                    if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                        $pers_foto = "http://" . MAIN_SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                    }
                    //$pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=/upload/" . $arFile["SUBDIR"]."/". urlencode($arFile["FILE_NAME"]) . "&w=242&h=242&zc=4";
                }

                if ($arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"]) $WORK_POSITION = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];

                $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                if ($arAnswer["SIMPLE_QUESTION_320"][0]) $WORK_CITY = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                if ($arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"]) $WORK_COUNTRY = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                if ($arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"]) $ABOUT = str_replace(array("'",'"',"&quot;"),"",$arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"]);

            }

            if (!$pers_foto) $pers_foto = 'http://'.SITE_URL_PATH.'/image.php?src=/nopic.jpg&w=242&h=242&zc=4';
            $PERSONAL_PHOTO = $pers_foto;

            //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];

        }
    }

    $fields = array(
        "UF_PERSONAL_PHOTO" => $PERSONAL_PHOTO,
        "UF_WORK_POSITION" => $WORK_POSITION,
        "UF_WORK_COUNTRY" => $WORK_COUNTRY,
        "UF_WORK_CITY" => $WORK_CITY,
        "UF_ABOUT" => $ABOUT,
    );


    //echo "<br>".$arUsers["ID"];
    /*echo "<pre>";
print_r($fields);
    echo "</pre>";*/
    $user = new CUser;
    $user->Update($arUsers["ID"], $fields);

    $cnt++;
}
echo "count: ".$cnt;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>