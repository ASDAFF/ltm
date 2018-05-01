<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$page_start = microtime();

CModule::IncludeModule('form');
CModule::IncludeModule('iblock');

$_REQUEST["exhb"] = 360;
//BXClearCache(true, "/rst/");
define("BX_COMP_MANAGED_CACHE", false);

$arSelect = Array("ID", "NAME", "PROPERTY_USER_GROUP_ID", "PROPERTY_UC_PARTICIPANTS_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP", "PROPERTY_DATE_TIME");
$arFilter = Array("IBLOCK_ID"=>15, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$_REQUEST["exhb"]);
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>1), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    define('APP_EXHIBITOR', $arFields["PROPERTY_USER_GROUP_ID_VALUE"]);
    define('APP_BUYER', $arFields["PROPERTY_C_GUESTS_GROUP_VALUE"]);
    //echo $arFields["PROPERTY_DATE_TIME_VALUE"]." ".strtotime($arFields["PROPERTY_DATE_TIME_VALUE"])." ".date("d.m.Y",strtotime($arFields["PROPERTY_DATE_TIME_VALUE"]));
    define('EXBDATE', $arFields["PROPERTY_DATE_TIME_VALUE"]);
}

$arGroups = CUser::GetUserGroup($USER->GetId());
foreach ($arGroups as $grp) {
    if ($grp == APP_BUYER) {
        define('CURRENT_USER_GROUP',$grp);
    }
    if ($grp==APP_EXHIBITOR) {
        define('CURRENT_USER_GROUP',$grp);
    }
}
define('MAIN_SITE_URL_PATH','luxurytravelmart.ru');
define('SITE_URL_PATH','app.luxurytravelmart.ru');
define('EXHIBITION_ID',1);

/*
    This is an example class script proceeding secured API
    To use this class you should keep same as query string and function name
    Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
         function delete_user(){
             You code goes here
         }
    Class will execute the function dynamically;

    usage :

        $object->response(output_data, status_code);
        $object->_request	- to get santinized input

        output_data : JSON (I am using)
        status_code : Send status message for headers

    Add This extension for localhost checking :
        Chrome Extension : Advanced REST client Application
        URL : https://chrome.google.com/webstore/detail/hgmloofddffdnphfgcellkdfbfbjeloo

    I used the below table for demo purpose.

    CREATE TABLE IF NOT EXISTS `users` (
      `user_id` int(11) NOT NULL AUTO_INCREMENT,
      `user_fullname` varchar(25) NOT NULL,
      `user_email` varchar(APP_EXHIBITOR) NOT NULL,
      `user_password` varchar(APP_EXHIBITOR) NOT NULL,
      `user_status` tinyint(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 */

require_once("Rest.inc.php");

class API extends REST {

    public $data = "";
    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = 'glkg$fhS%jdZ';
    const DB = "sitemanager0";

    private $db = NULL;


    public function __construct(){
        parent::__construct();
        $this->dbConnect();
    }

    /*
     *  Database connection
    */
    private function dbConnect(){
        $this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
        if($this->db)
            mysql_select_db(self::DB,$this->db);
    }

    /*
     * Public method for access api.
     * This method dynmically call the method based on the query string
     *
     */
    public function processApi()
    {
        global $USER;

        BXClearCache(true, "/rst/");

        $fp = fopen($_SERVER["DOCUMENT_ROOT"] . '/rst/log.txt', 'a');
        $str = date("d.m.Y H:i:s", time()) . " - " . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER["REQUEST_URI"] ." - auth ".$USER->IsAuthorized(). PHP_EOL;
        fwrite($fp, $str);
        fclose($fp);


        if (!$USER->IsAuthorized() AND !$_REQUEST["auth_token"]) {
            $arrError = array("error_code" => 401, "error_message" => "User not authenticated 1");
            $this->response($this->json($arrError), 200);
        } elseif ($_REQUEST["auth_token"]) {

            $filter = array(
                "UF_AUTH_TOKEN" => $_REQUEST["auth_token"],
                "UF_AUTH_TOKEN_EXACT_MATCH" => "Y"
            );
            $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $filter, array("SELECT" => array("UF_*")));
            while ($arUsers = $rsUsers->getNext()) {
                $user_id = $arUsers["ID"];
            }


            $arSelect = Array("ID", "NAME", "PROPERTY_APP_OFF");
            $arFilter = Array("IBLOCK_ID"=>24, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>13909);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
            while($ob = $res->GetNextElement()){
                $arFields = $ob->GetFields();
                $arFields["PROPERTY_APP_OFF_VALUE"] = '';
                if ($arFields["PROPERTY_APP_OFF_VALUE"]==1) {
                    $arrError = array("error_code" => 401, "error_message" => "User not authenticated 2");
                    $this->response($this->json($arrError), 200);
                } else {
                    if (!$USER->IsAuthorized())
                    if (intval($user_id) > 0) CUser::Authorize($user_id);
                }
            }
            /*} else {
                if (intval($user_id) > 0) {
                    $fp = fopen($_SERVER["DOCUMENT_ROOT"] . '/rst/logout.txt', 'a');
                    $str = date("d.m.Y H:i:s", time()) . " - " . $user_id. " - ". $USER->getId()." - ".$_SERVER["REMOTE_ADDR"] . " - " . $_SERVER["REQUEST_URI"] . PHP_EOL;
                    fwrite($fp, $str);
                    fclose($fp);

                    $USER->Logout();
                    CUser::Authorize($user_id);
                }
            }*/
        } else {

            $arrError = array("error_code" => 401, "error_message" => "User not authenticated 3");
            $this->response($this->json($arrError), 200);
        }


        if (!$USER->GetId()) {
            $arrError = array("error_code" => 401, "error_message" => "User not authenticated 4");
            $this->response($this->json($arrError), 200);
        }

        $rsUser = CUser::GetByID($USER->GetId());
        $arUser = $rsUser->Fetch();
        if ($arUser["UF_APP_OFF"]) {
            $arrError = array("error_code" => 401, "error_message" => "User not authenticated 5");
            $this->response($this->json($arrError), 200);
        }

        $func = strtolower(trim(str_replace("/", "", $_REQUEST['call'])));
        if ((int)method_exists($this, $func) > 0) {
            $this->$func();
        } else {
            $arrError = array("error_code"=>404, "error_message"=>"Invalid request");
            $this->response($this->json($arrError), 200);
        }
    }

    private function user(){
        global $USER;
        global $DB;
        // Cross validation if the request method is GET else it will return "Not Acceptable" status
        if($this->get_request_method() != "GET" AND $this->get_request_method() != "POST"){
            $arrError = array("error_code" => 406, "error_message" => "Wrong request method");
            $this->response($this->json($arrError), 200);
        }



        if ($this->_request['func']=="getSelf" AND $this->get_request_method() == "GET") {
            if ($this->_request['login']) {

                $arFilter = array("LOGIN"=>$this->_request['login']);
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter, array("SELECT"=>array("UF_WORK_COUNTRY","UF_WORK_CITY","UF_ABOUT","UF_WORK_POSITION","UF_PERSONAL_PHOTO","UF_IS_ONLINE","UF_FAVORITES","UF_BLOCK_LIST")));
                while($arUsers = $rsUsers->getNext()) {
                    $result["ID"] = $arUsers["ID"];
                    $result["NAME"] = $arUsers["NAME"];
                    $result["LAST_NAME"] = $arUsers["LAST_NAME"];
                    $result["WORK_COMPANY"] = str_replace(array("'",'"',"&quot;"),"",$arUsers["WORK_COMPANY"]);

                    $result["WORK_COUNTRY"] = $arUsers["UF_WORK_COUNTRY"];
                    $result["WORK_CITY"] = $arUsers["UF_WORK_CITY"];
                    $result["ABOUT"] = $arUsers["UF_ABOUT"];
                    $result["WORK_POSITION"] = $arUsers["UF_WORK_POSITION"];
                    $result["PERSONAL_PHOTO"] = $arUsers["UF_PERSONAL_PHOTO"];

                    unset($pers_foto);
                    unset($arrrole);

                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if ($grp==APP_BUYER) {
                            $arrrole[] = 'Buyer';
                        }
                        if ($grp==APP_EXHIBITOR) {
                            $arrrole[] = 'Exhibitor';
                        }
                        if ($grp==54) {
                            $arrrole[] = 'Administrator';
                        }
                    }


                    if (!empty($arrrole)) $role = implode(", ", $arrrole);
                    $result["ROLE"] = iconv("CP1251","UTF-8",$role);

                    if ($arUsers["UF_IS_ONLINE"]) $result["IS_ONLINE"] = 1; else $result["IS_ONLINE"] = 0;

                    $arFilter2 = array("ID"=>CUser::GetID(), "UF_FAVORITES"=>$arUsers["ID"]);
                    $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                    while($arUsers2 = $rsUsers2->getNext()) {
                        $is_fav = 1;
                    }
                    if ($is_fav) $result["IS_FAVORITE"] = 1; else $result["IS_FAVORITE"] = 0;

                }

                if (is_array($result)) {
                    $this->response($this->json($result), 200);
                } else {
                    $arrError = array("error_code"=>204, "error_message"=>"User not found");
                    $this->response($this->json($arrError), 200);
                }
            } else {
                $arrError = array("error_code"=>400, "error_message"=>"Wrong user id");
                $this->response($this->json($arrError), 200);
            }
        } elseif ($this->_request['func']=="getInfo" AND $this->get_request_method() == "GET") {

            if (intval($this->_request['id'])>0) {

                $arFilter = array("ID"=>$this->_request['id']);
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter, array("SELECT"=>array("UF_WORK_COUNTRY","UF_WORK_CITY","UF_ABOUT","UF_WORK_POSITION","UF_PERSONAL_PHOTO","UF_IS_ONLINE","UF_FAVORITES","UF_BLOCK_LIST")));
                while($arUsers = $rsUsers->getNext()) {
                    $result["ID"] = $arUsers["ID"];
                    $result["NAME"] = $arUsers["NAME"];//$arUsers["NAME"];
                    $result["LAST_NAME"] = $arUsers["LAST_NAME"];//$arUsers["LAST_NAME"];
                    $result["WORK_COMPANY"] = str_replace(array("'",'"',"&quot;"),"",$arUsers["WORK_COMPANY"]);//str_replace(array("'",'"',"&quot;"),"",$arUsers["WORK_COMPANY"]);
                    //$result["PERSONAL_COUNTRY"] = $arUsers["PERSONAL_COUNTRY"];//$arUsers["PERSONAL_COUNTRY"];
                    //$result["WORK_COUNTRY"] = $arUsers["WORK_COUNTRY"];//$arUsers["WORK_COUNTRY"];
                    //$arUsers["PERSONAL_PHOTO"];
                    $role = '';

                    $result["WORK_COUNTRY"] = $arUsers["UF_WORK_COUNTRY"];
                    $result["WORK_CITY"] = $arUsers["UF_WORK_CITY"];
                    $result["ABOUT"] = $arUsers["UF_ABOUT"];
                    $result["WORK_POSITION"] = $arUsers["UF_WORK_POSITION"];
                    $result["PERSONAL_PHOTO"] = $arUsers["UF_PERSONAL_PHOTO"];

                    if (intval($this->_request['id'])==$USER->GetId()) {
                        if (substr_count($result["PERSONAL_PHOTO"],"nopic")>0) {
                            $result["PERSONAL_PHOTO"] = "http://app.luxurytravelmart.ru/image.php?src=/upload-userpic.jpg&w=242&h=242&zc=4";
                        }
                    }


                    unset($pers_foto);
                    unset($arrrole);

                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if ($grp==APP_BUYER) {
                            $arrrole[] = 'Buyer';
                        }
                        if ($grp==APP_EXHIBITOR) {
                            $arrrole[] = 'Exhibitor';
                        }
                        if ($grp==54) {
                            $arrrole[] = 'Administrator';


                            $result["WORK_COMPANY"] = "Luxury Travel Mart";
                            $result["IS_ONLINE"] = 1;
                            $result["PERSONAL_PHOTO"] = "http://".SITE_URL_PATH."/image.php?src=".CFile::GetPath($arUsers["PERSONAL_PHOTO"])."&w=242&h=242&zc=4";
                            $result["WORK_COUNTRY"] = "International";
                            $result["WORK_CITY"] = "";
                            $result["ABOUT"] = "<p>Since its very first year, Luxury Travel Mart has become the most successful event in the luxury travel segment in Russia and boarding countries. This is a B2B event for companies that offer luxury products, who wish to establish new contacts and strengthen old ties on the Russian and CIS markets.</p>
<p>Companies that participate in the Luxury Travel Mart include five-star hotels and resorts, elite car companies, cruise and yacht charter companies, private jet charters, DMC and luxury operators. Most of the best-known brands in the luxury travel industry have participated and continue to participate in LTM each year.</p>
<p>This B2B event has been carefully thought out and planned in such a way that sellers and potential buyers of services – companies working with VIP clients, have the opportunity to establish partnerships within the space of a single day. The main aim of the of the organisers of the Luxury Travel Mart is to expand the geography of sales of the exhibition’s participants and also to introduce players to new areas and services to which they previously did not have access.</p>";
                            $result["WORK_POSITION"] = "Administrator";
                        }
                    }

                    //"http://".SITE_URL_PATH."/image.php?src=".CFile::GetPath($arUsers["PERSONAL_PHOTO"])."&w=1000&h=1000&zc=4";//$arUsers["PERSONAL_PHOTO"];
                    //$result["WORK_POSITION"] = $arUsers["PERSONAL_PROFESSION"];//$arUsers["PERSONAL_PROFESSION"];

                    if (!empty($arrrole)) $role = implode(", ", $arrrole);
                    $result["ROLE"] = iconv("CP1251","UTF-8",$role);

                    if ($arUsers["UF_IS_ONLINE"]) $result["IS_ONLINE"] = 1; else $result["IS_ONLINE"] = 0;

                    $arFilter2 = array("ID"=>CUser::GetID(), "UF_FAVORITES"=>$arUsers["ID"]);
                    $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                    while($arUsers2 = $rsUsers2->getNext()) {
                        $is_fav = 1;
                    }

                    $sql = mysql_query("SELECT * FROM `chat_messages` WHERE (`AUTHOR_ID` = '" . $arUsers["ID"] . "' AND `TO_USER` = '" . $USER->GetID() . "') OR (`AUTHOR_ID` = '" . $USER->GetID() . "' AND `TO_USER` = '" . $arUsers["ID"] . "') ORDER BY ID DESC", $this->db);
                    $result["MESSAGES_COUNT"] = intval(mysql_num_rows($sql));

                    /*
                    if (!empty($arUsers["UF_FAVORITES"])) {
                        foreach ($arUsers["UF_FAVORITES"] as $fav) {
                            if ($fav==CUser::GetID()) $is_fav = 1;
                        }
                    }*/
                    if ($is_fav) $result["IS_FAVORITE"] = 1; else $result["IS_FAVORITE"] = 0;
                }

                if (is_array($result))
                    $this->response($this->json($result), 200);
                else {
                    $arrError = array("error_code" => 204, "error_message" => "User not found");
                    $this->response($this->json($arrError), 200);
                }
            } else {
                $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
                $this->response($this->json($result),200);
            }

        } elseif ($_REQUEST['func']=="addAvatar" AND $this->get_request_method() == "POST") {

            //intval($this->_request['user_id'])==CUser::GetID() AND
            if ( $this->_request['avatar'] ) {


                /*

                $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                while($arUsers2 = $rsUsers2->getNext()) {
                    $personal_foto = $arUsers2["PERSONAL_PHOTO"];
                }
*/

                    //$data = explode(',', $this->_request['avatar']);
                    $data = $this->_request['avatar'];
                    $output_file = $_SERVER["DOCUMENT_ROOT"]."/upload/tmp_avatar/".CUser::GetID()."_".time().".png";
                    $ifp = fopen($output_file, "wb");
                    fwrite($ifp, base64_decode($data));
                    fclose($ifp);

/*
                $ifp2 = fopen($_SERVER["DOCUMENT_ROOT"]."/upload/tmp_avatar/data3.txt", "wb");
                fwrite($ifp2, base64_decode($data));
                fclose($ifp2);
*/

                $arFile = CFile::MakeFileArray($output_file);
                /*
                if (intval($personal_foto)>0) {
                    $arFile['del'] = "Y";
                    $arFile['old_file'] = $personal_foto;
                }
*/
                $arFilter2 = array("ID"=>CUser::GetID());
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter2, array("SELECT"=>array("UF_*")));
                while($arUsers = $rsUsers->getNext()) {
                    $is_fav = '';

                    //print_r($arUsers);

                    $arrFRMSid = array($arUsers["UF_MSCSPRING2016"], $arUsers["UF_ID"], $arUsers["UF_ID5"], $arUsers["UF_ID6"], $arUsers["UF_ID_COMP"], $arUsers["UF_ID2"], $arUsers["UF_ID3"], $arUsers["UF_ID4"], $arUsers["UF_ID7"], $arUsers["UF_ID8"], $arUsers["UF_ID12"], $arUsers["UF_ID11"], $arUsers["UF_ID10"], $arUsers["UF_ID_GROUP"]);
                    unset($pers_foto);
                    unset($arrrole);

                    //print_r($arrFRMSid);

                    foreach ($arrFRMSid as $frm_id) {
                        unset($arValues);
                        if ($frm_id) {

                            $rsAnswers = CFormAnswer::GetList(579,$by="s_id",$order="desc");
                            while ($arAnswer = $rsAnswers->Fetch()) $ansId = $arAnswer["ID"];
                            CFormResult::SetField($frm_id, "SIMPLE_QUESTION_575", array($ansId=>$arFile));
                            //echo "<br>1 ".$ansId." ggg ".$frm_id;

                            $rsAnswers = CFormAnswer::GetList(494,$by="s_id",$order="desc");
                            while ($arAnswer = $rsAnswers->Fetch()) $ansId = $arAnswer["ID"];
                            CFormResult::SetField($frm_id, "SIMPLE_QUESTION_269", array($ansId=>$arFile));
                            //echo "<br>2 ".$ansId." ggg ".$frm_id;

                            /*
                            $rsAnswers = CFormAnswer::GetList(494,$by="s_id",$order="desc");
                            while ($arAnswer = $rsAnswers->Fetch()) $ansId = $arAnswer["ID"];
                            CFormResult::SetField($frm_id, "SIMPLE_QUESTION_269", array($ansId=>$arFile));

                            $rsAnswers = CFormAnswer::GetList(579,$by="s_id",$order="desc");
                            while ($arAnswer = $rsAnswers->Fetch()) $ansId = $arAnswer["ID"];
                            CFormResult::SetField($frm_id, "SIMPLE_QUESTION_575", array($ansId=>$arFile));

                            $rsAnswers = CFormAnswer::GetList(105,$by="s_id",$order="desc");
                            while ($arAnswer = $rsAnswers->Fetch()) $ansId = $arAnswer["ID"];
                            CFormResult::SetField($frm_id, "SIMPLE_QUESTION_772", array($ansId=>$arFile));
                            */
                        }
                    }


                    foreach ($arrFRMSid as $frm_id) {
                        $arAnswer = CFormResult::GetDataByID($frm_id, array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391", "SIMPLE_QUESTION_575", "SIMPLE_QUESTION_772",));

                        if ($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"] > 0) {

                            $file = "http://" . SITE_URL_PATH . "/" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"]);
                            $file_headers = get_headers($file);
                            if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                                $pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"]) . "&w=242&h=242&zc=4";
                            }
                            $file = "http://" . MAIN_SITE_URL_PATH . "/" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"]);
                            $file_headers = get_headers($file);
                            if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                                $pers_foto = "http://" . MAIN_SITE_URL_PATH . "/image.php?src=" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"]) . "&w=242&h=242&zc=4";
                            }

                        } elseif ($arAnswer["SIMPLE_QUESTION_575"][0]["USER_FILE_ID"] > 0) {

                            $file = "http://" . SITE_URL_PATH . "/" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_575"][0]["USER_FILE_ID"]);
                            $file_headers = get_headers($file);
                            if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                                $pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_575"][0]["USER_FILE_ID"]) . "&w=242&h=242&zc=4";
                            }
                            $file = "http://" . MAIN_SITE_URL_PATH . "/" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_575"][0]["USER_FILE_ID"]);
                            $file_headers = get_headers($file);
                            if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                                $pers_foto = "http://" . MAIN_SITE_URL_PATH . "/image.php?src=" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_575"][0]["USER_FILE_ID"]) . "&w=242&h=242&zc=4";
                            }
                            //$pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_575"][0]["USER_FILE_ID"]) . "&w=242&h=242&zc=4";
                        } elseif ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"] > 0) {

                            $file = "http://" . SITE_URL_PATH . "/" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]);
                            $file_headers = get_headers($file);
                            if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                                $pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]) . "&w=242&h=242&zc=4";
                            }
                            $file = "http://" . MAIN_SITE_URL_PATH . "/" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]);
                            $file_headers = get_headers($file);
                            if ($file_headers[0] != 'HTTP/1.1 404 Not Found') {
                                $pers_foto = "http://" . MAIN_SITE_URL_PATH . "/image.php?src=" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]) . "&w=242&h=242&zc=4";
                            }
                            //$pers_foto = "http://" . SITE_URL_PATH . "/image.php?src=" . CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]) . "&w=242&h=242&zc=4";
                        }
                    }

                    if (!$pers_foto) $pers_foto = 'http://'.SITE_URL_PATH.'/image.php?src=/nopic.jpg&w=242&h=242&zc=4';
                    $PERSONAL_PHOTO = $pers_foto;

                    $fields = array(
                        "UF_PERSONAL_PHOTO" => $PERSONAL_PHOTO
                    );
                    $user = new CUser;
                    $user->Update($arUsers["ID"], $fields);


                }

                $this->response($this->json(array("MESSAGE"=>"OK")), 200);

                //$fields = Array("PERSONAL_PHOTO"=>$arFile);
                //$USER->Update(CUser::GetID(), $fields);

            } else {
                $arrError = array("error_code" => 204, "error_message" => "No file uploaded");
                $this->response($this->json($arrError), 200);
            }

        } elseif ($this->_request['func']=="getExhibitorsList" AND $this->get_request_method() == "GET") {

            //if ($this->_request['login']) {
//"LOGIN"=>$this->_request['login'],


            $rsUserMe = $USER->GetByID(CUser::GetID());
            $arUserMe = $rsUserMe->Fetch();
            $blocked_list = $arUserMe["UF_BLOCK_LIST"];

            $offset = 0;
            $limit = 20;
            if (intval($this->_request["limit"])>0) $limit = intval($this->_request["limit"]);
            if (intval($this->_request["offset"])>0) $offset = intval($this->_request["offset"]);
            $k=0;

            $arFilter = array("GROUPS_ID"=>array(APP_EXHIBITOR));
            if (!empty($blocked_list)) $arFilter["ID"] = "~".implode(" & ~",$blocked_list);

            $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter, array("NAV_PARAMS"=>array("nPageSize"=>$limit,"iNumPage"=>$offset), "SELECT"=>array("UF_WORK_COUNTRY","UF_WORK_CITY","UF_ABOUT","UF_WORK_POSITION","UF_PERSONAL_PHOTO","UF_IS_ONLINE","UF_FAVORITES","UF_BLOCK_LIST")));
            $rsUsers->NavStart($limit);
            while($arUsers = $rsUsers->getNext()) {
                $is_fav='';
                $result[$k]["ID"] = $arUsers["ID"];
                $result[$k]["NAME"] = ucfirst($arUsers["NAME"]);
                $result[$k]["LAST_NAME"] = $arUsers["LAST_NAME"];
                $result[$k]["WORK_COMPANY"] = str_replace(array("'",'"',"&quot;"),"",$arUsers["WORK_COMPANY"]);

                $result[$k]["WORK_COUNTRY"] = $arUsers["UF_WORK_COUNTRY"];
                $result[$k]["WORK_CITY"] = $arUsers["UF_WORK_CITY"];
                $result[$k]["ABOUT"] = $arUsers["UF_ABOUT"];
                $result[$k]["WORK_POSITION"] = $arUsers["UF_WORK_POSITION"];
                $result[$k]["PERSONAL_PHOTO"] = $arUsers["UF_PERSONAL_PHOTO"];
                $role = '';

                unset($pers_foto);
                unset($arrrole);

                $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                foreach ($arGroups as $grp) {
                    if ($grp==APP_BUYER) {
                        $arrrole[] = 'Buyer';
                    }
                    if ($grp==APP_EXHIBITOR) {
                        $arrrole[] = 'Exhibitor';
                    }
                }

                if (!empty($arrrole)) $role = implode(", ", $arrrole);
                $result[$k]["ROLE"] = iconv("CP1251","UTF-8",$role);

                if ($arUsers["UF_IS_ONLINE"]) $result[$k]["IS_ONLINE"] = 1; else $result[$k]["IS_ONLINE"] = 0;

                $arFilter2 = array("ID"=>CUser::GetID(), "UF_FAVORITES"=>$arUsers["ID"]);
                $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                while($arUsers2 = $rsUsers2->getNext()) {
                    $is_fav = 1;
                }
                if ($is_fav) $result[$k]["IS_FAVORITE"] = 1; else $result[$k]["IS_FAVORITE"] = 0;


                $k++;
            }

            if (is_array($result))
                $this->response($this->json($result), 200);
            else {
                $arrError = array("error_code" => 204, "error_message" => "Empty list");
                $this->response($this->json($arrError), 200);
            }
            /*} else {
                $this->response('',400);
            }*/
        } elseif ($this->_request['func']=="getBuyersList" AND $this->get_request_method() == "GET") {

            //if ($this->_request['login']) {
//"LOGIN"=>$this->_request['login'],
            $xt = explode(" ",microtime());
            $page_start = $xt[1]+$xt[0];

            $rsUserMe = $USER->GetByID(CUser::GetID());
            $arUserMe = $rsUserMe->Fetch();
            $blocked_list = $arUserMe["UF_BLOCK_LIST"];

            $limit = 20;
            $offset = 0;
            if (intval($this->_request["limit"])>0) $limit = intval($this->_request["limit"]);
            if (intval($this->_request["offset"])>0) $offset = intval($this->_request["offset"]);
            $k=0;
            $arFilter = array( "GROUPS_ID"=>array(APP_BUYER));
            if (!empty($blocked_list)) $arFilter["ID"] = "~".implode(" & ~",$blocked_list);

            $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter, array("NAV_PARAMS"=>array("nPageSize"=>$limit,"iNumPage"=>$offset), "SELECT"=>array("UF_WORK_COUNTRY","UF_WORK_CITY","UF_ABOUT","UF_WORK_POSITION","UF_PERSONAL_PHOTO","UF_IS_ONLINE","UF_FAVORITES","UF_BLOCK_LIST")));
            //$rsUsers->NavStart($limit);
            while($arUsers = $rsUsers->getNext()) {
                $is_fav = '';
                $result[$k]["ID"] = $arUsers["ID"];
                $result[$k]["NAME"] = ucfirst($arUsers["NAME"]);
                $result[$k]["LAST_NAME"] = $arUsers["LAST_NAME"];
                $result[$k]["WORK_COMPANY"] = str_replace(array("'",'"',"&quot;"),"",$arUsers["WORK_COMPANY"]);

                $result[$k]["WORK_COUNTRY"] = $arUsers["UF_WORK_COUNTRY"];
                $result[$k]["WORK_CITY"] = $arUsers["UF_WORK_CITY"];
                $result[$k]["ABOUT"] = $arUsers["UF_ABOUT"];
                $result[$k]["WORK_POSITION"] = $arUsers["UF_WORK_POSITION"];
                $result[$k]["PERSONAL_PHOTO"] = $arUsers["UF_PERSONAL_PHOTO"];

                unset($pers_foto);
                unset($arrrole);

                $role = '';
                $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                foreach ($arGroups as $grp) {
                    if ($grp==APP_BUYER) {
                        $arrrole[] = 'Buyer';
                    }
                    if ($grp==APP_EXHIBITOR) {
                        $arrrole[] = 'Exhibitor';
                    }
                }
                if (!empty($arrrole)) $role = implode(", ", $arrrole);
                $result[$k]["ROLE"] = iconv("CP1251","UTF-8",$role);

                if ($arUsers["UF_IS_ONLINE"]) $result[$k]["IS_ONLINE"] = 1; else $result[$k]["IS_ONLINE"] = 0;

                $arFilter2 = array("ID"=>CUser::GetID(), "UF_FAVORITES"=>$arUsers["ID"]);
                $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                while($arUsers2 = $rsUsers2->getNext()) {
                    $is_fav = 1;
                }
                if ($is_fav) $result[$k]["IS_FAVORITE"] = 1; else $result[$k]["IS_FAVORITE"] = 0;

                $k++;
            }

            $xt = explode(" ",microtime());
            $page_end = $xt[1]+$xt[0];

            $generate = ($page_end - $page_start);
//. implode(",",$_SERVER)
            $fp = fopen($_SERVER["DOCUMENT_ROOT"] . '/rst/log_size.txt', 'a');
            $str = date("d.m.Y H:i:s", time()) . " - " . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER["REQUEST_URI"] . " scriptTime: ". $generate .  PHP_EOL;
            fwrite($fp, $str);
            fclose($fp);


            if (is_array($result))
                $this->response($this->json($result), 200);
            else {
                $arrError = array("error_code" => 204, "error_message" => "Empty list");
                $this->response($this->json($arrError), 200);
            }
            /*} else {
                $this->response('',400);
            }
*/

        } elseif ($this->_request['func']=="getFavorites" AND $this->get_request_method() == "GET") {

            $rsUserMe = $USER->GetByID($USER->GetID());
            $arUserMe = $rsUserMe->Fetch();
            //echo $USER->GetID();
            //print_r($_SESSION);


            $k=0;
            foreach ($arUserMe["UF_FAVORITES"] as $fav) {

                $is_fav = '';
                $rsUser = $USER->GetByID($fav);
                $arUser = $rsUser->Fetch();

                $result[$k]["ID"] = $arUser["ID"];
                $result[$k]["NAME"] = $arUser["NAME"];
                $result[$k]["LAST_NAME"] = $arUser["LAST_NAME"];
                $result[$k]["WORK_COMPANY"] = str_replace(array("'",'"',"&quot;"),"",$arUser["WORK_COMPANY"]);

                $result[$k]["WORK_COUNTRY"] = $arUser["UF_WORK_COUNTRY"];
                $result[$k]["WORK_CITY"] = $arUser["UF_WORK_CITY"];
                $result[$k]["ABOUT"] = $arUser["UF_ABOUT"];
                $result[$k]["WORK_POSITION"] = $arUser["UF_WORK_POSITION"];
                $result[$k]["PERSONAL_PHOTO"] = $arUser["UF_PERSONAL_PHOTO"];

                $role = '';
                unset($pers_foto);
                unset($arrrole);

                $arGroups = CUser::GetUserGroup($arUser["ID"]);
                foreach ($arGroups as $grp) {
                    if ($grp==APP_BUYER) {
                        $arrrole[] = 'Buyer';
                    }
                    if ($grp==APP_EXHIBITOR) {
                        $arrrole[] = 'Exhibitor';
                    }
                }

                if (!empty($arrrole)) $role = implode(", ", $arrrole);
                $result[$k]["ROLE"] = iconv("CP1251","UTF-8",$role);
                if ($arUser["UF_IS_ONLINE"]) $result[$k]["IS_ONLINE"] = 1; else $result[$k]["IS_ONLINE"] = 0;
                $arFilter2 = array("ID"=>$row["SENDER_ID"], "UF_FAVORITES"=>$arUsers["ID"]);
                $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                while($arUsers2 = $rsUsers2->getNext()) {
                    $is_fav = 1;
                }

                $sql = mysql_query("SELECT * FROM `chat_messages` WHERE (`AUTHOR_ID` = '" . $arUser["ID"] . "' AND `TO_USER` = '" . $USER->GetID() . "') OR (`AUTHOR_ID` = '" . $USER->GetID() . "' AND `TO_USER` = '" . $arUser["ID"] . "')  ORDER BY ID DESC", $this->db);
                $result[$k]["MESSAGES_COUNT"] = intval(mysql_num_rows($sql));


                $k++;
            }

            if (is_array($result))
                $this->response($this->json($result), 200);
            else {
                $arrError = array("error_code" => 204, "error_message" => "Empty list");
                $this->response($this->json($arrError), 200);
            }

        } elseif ($this->_request['func']=="find" AND $this->get_request_method() == "GET") {

            $arrIds = array();
            if ($this->_request["q"]) {

                $arFilter = array("NAME"=>$this->_request["q"]."%");
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                while($arUsers = $rsUsers->getNext()) {
                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if (($grp == APP_BUYER AND CURRENT_USER_GROUP == APP_EXHIBITOR) OR ($grp == APP_EXHIBITOR AND CURRENT_USER_GROUP == APP_BUYER)) $arrIds[$arUsers["ID"]] = $arUsers["ID"];
                    }
                }
                $arFilter = array("LAST_NAME"=>$this->_request["q"]."%");
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                while($arUsers = $rsUsers->getNext()) {
                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if (($grp == APP_BUYER AND CURRENT_USER_GROUP == APP_EXHIBITOR) OR ($grp == APP_EXHIBITOR AND CURRENT_USER_GROUP == APP_BUYER)) $arrIds[$arUsers["ID"]] = $arUsers["ID"];
                    }
                }
                $arFilter = array("LOGIN"=>$this->_request["q"]."%");
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                while($arUsers = $rsUsers->getNext()) {
                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if (($grp == APP_BUYER AND CURRENT_USER_GROUP == APP_EXHIBITOR) OR ($grp == APP_EXHIBITOR AND CURRENT_USER_GROUP == APP_BUYER)) $arrIds[$arUsers["ID"]] = $arUsers["ID"];
                    }
                }
                $arFilter = array("EMAIL"=>$this->_request["q"]."%");
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                while($arUsers = $rsUsers->getNext()) {
                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if (($grp == APP_BUYER AND CURRENT_USER_GROUP == APP_EXHIBITOR) OR ($grp == APP_EXHIBITOR AND CURRENT_USER_GROUP == APP_BUYER)) $arrIds[$arUsers["ID"]] = $arUsers["ID"];
                    }
                }
                $arFilter = array("KEYWORDS"=>$this->_request["q"]."%");
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                while($arUsers = $rsUsers->getNext()) {
                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if (($grp == APP_BUYER AND CURRENT_USER_GROUP == APP_EXHIBITOR) OR ($grp == APP_EXHIBITOR AND CURRENT_USER_GROUP == APP_BUYER)) $arrIds[$arUsers["ID"]] = $arUsers["ID"];
                    }
                }
            } else {
                $arrError = array("error_code" => 204, "error_message" => "Empty list");
                $this->response($this->json($arrError), 200);
            }


            if (!empty($arrIds)) {

                $limit = 20;
                $offset = 1;
                if (intval($this->_request["limit"]) > 0) $limit = intval($this->_request["limit"]);
                if (intval($this->_request["offset"]) > 0) $offset = intval($this->_request["offset"]);
                $k = 0;
                $arFilter = array("ID" => implode(" | ", $arrIds));
                $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $arFilter, array("NAV_PARAMS" => array("nPageSize" => $limit, "iNumPage" => $offset), "SELECT"=>array("UF_WORK_COUNTRY","UF_WORK_CITY","UF_ABOUT","UF_WORK_POSITION","UF_PERSONAL_PHOTO","UF_IS_ONLINE","UF_FAVORITES","UF_BLOCK_LIST")));
                //$rsUsers->NavStart($limit);
                while ($arUsers = $rsUsers->getNext()) {

                    unset($show);


                    //if ($show) {

                    $is_fav = '';
                    $result[$k]["ID"] = $arUsers["ID"];
                    $result[$k]["NAME"] = $arUsers["NAME"];
                    $result[$k]["LAST_NAME"] = $arUsers["LAST_NAME"];
                    $result[$k]["WORK_COMPANY"] = str_replace(array("'",'"',"&quot;"),"",$arUsers["WORK_COMPANY"]);

                    $result[$k]["WORK_COUNTRY"] = $arUsers["UF_WORK_COUNTRY"];
                    $result[$k]["WORK_CITY"] = $arUsers["UF_WORK_CITY"];
                    $result[$k]["ABOUT"] = $arUsers["UF_ABOUT"];
                    $result[$k]["WORK_POSITION"] = $arUsers["UF_WORK_POSITION"];
                    $result[$k]["PERSONAL_PHOTO"] = $arUsers["UF_PERSONAL_PHOTO"];

                    $role = '';
                    unset($pers_foto);
                    unset($arrrole);

                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if ($grp == APP_BUYER) {
                            $arrrole[] = 'Buyer';
                        }
                        if ($grp == APP_EXHIBITOR) {
                            $arrrole[] = 'Exhibitor';
                        }
                    }
                    if (!empty($arrrole)) $role = implode(", ", $arrrole);
                    $result[$k]["ROLE"] = iconv("CP1251", "UTF-8", $role);

                    if ($arUsers["UF_IS_ONLINE"]) $result[$k]["IS_ONLINE"] = 1; else $result[$k]["IS_ONLINE"] = 0;

                    $arFilter2 = array("ID" => CUser::GetID(), "UF_FAVORITES" => $arUsers["ID"]);
                    $rsUsers2 = CUser::GetList(($by = "id"), ($order = "desc"), $arFilter2);
                    while ($arUsers2 = $rsUsers2->getNext()) {
                        $is_fav = 1;
                    }
                    if ($is_fav) $result[$k]["IS_FAVORITE"] = 1; else $result[$k]["IS_FAVORITE"] = 0;
                    $k++;

                //}
                }

                if (is_array($result))
                    $this->response($this->json($result), 200);
                else {
                    $arrError = array("error_code" => 204, "error_message" => "Empty list");
                    $this->response($this->json($arrError), 200);
                }

            } else {
                $arrError = array("error_code" => 204, "error_message" => "Empty list");
                $this->response($this->json($arrError), 200);
            }

        } elseif ($this->_request['func']=="getNearUser" AND $this->get_request_method() == "GET") {

            $limit = 20;
            $offset = 1;
            if (intval($this->_request["limit"])>0) $limit = intval($this->_request["limit"]);
            if (intval($this->_request["offset"])>0) $offset = intval($this->_request["offset"]);

            $rsUserMe = $USER->GetByID(CUser::GetID());
            $arUserMe = $rsUserMe->Fetch();
            $blocked_list = $arUserMe["UF_BLOCK_LIST"];

            /*
                            round( 6378137 * acos( cos( $lat1 ) * cos( $lat2 ) * cos( $lng1 - $lng2 ) + sin( $lat1 ) * sin( $lat2 ) ) );
                            round( 6378137 * acos( 0,55201019 * 0,552010336 * 1 + 0,996114931 * 0,99611493 ) );
                            round( 6378137 * acos( 1,296960285 ) )
            function distance($lat1,$lng1,$lat2,$lng2)      {
                $lat1 = 56.49500;
                $lng1 = 84.947833;
                $lat2 = 56.49050;
                $lng2 = 84.947383;
                $lat1=deg2rad($lat1);
                $lng1=deg2rad($lng1);
                $lat2=deg2rad($lat2);
                $lng2=deg2rad($lng2);
                $delta_lat=($lat2 - $lat1);
                $delta_lng=($lng2 - $lng1);
                return round( 6378137 * acos( cos( $lat1 ) * cos( $lat2 ) * cos( $lng1 - $lng2 ) + sin( $lat1 ) * sin( $lat2 ) ) );
            }
                            */
            //0.00045 - koef = APP_EXHIBITOR0 meters
            $rsUserMe = $USER->GetByID($USER->GetID());
            $arUserMe = $rsUserMe->Fetch();
            $FindLatMin = $arUserMe["UF_LAT"] - 0.0045;
            $FindLatMax = $arUserMe["UF_LAT"] + 0.0045;
            $FindLngMin = $arUserMe["UF_LNG"] - 0.0045;
            $FindLngMax = $arUserMe["UF_LNG"] + 0.0045;

            $k=0;

            $arFilter = array("GROUPS_ID"=>array(54));
            $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
            $rsUsers->NavStart($limit);
            while($arUser = $rsUsers->getNext()) {
                $result["ADMIN"]["ID"] = $arUser["ID"];
                $result["ADMIN"]["NAME"] = $arUser["NAME"];
                $result["ADMIN"]["LAST_NAME"] = $arUser["LAST_NAME"];
                $result["ADMIN"]["WORK_COMPANY"] = "Luxury Travel Mart";

                $result["ADMIN"]["IS_ONLINE"] = 1;
                $result["ADMIN"]["PERSONAL_PHOTO"] = "http://".SITE_URL_PATH."/image.php?src=".CFile::GetPath($arUser["PERSONAL_PHOTO"])."&w=242&h=242&zc=4";
                $result["ADMIN"]["WORK_COUNTRY"] = "International";
                $result["ADMIN"]["WORK_CITY"] = "";
                $result["ADMIN"]["ABOUT"] = "<p>Since its very first year, Luxury Travel Mart has become the most successful event in the luxury travel segment in Russia and boarding countries. This is a B2B event for companies that offer luxury products, who wish to establish new contacts and strengthen old ties on the Russian and CIS markets.</p>
<p>Companies that participate in the Luxury Travel Mart include five-star hotels and resorts, elite car companies, cruise and yacht charter companies, private jet charters, DMC and luxury operators. Most of the best-known brands in the luxury travel industry have participated and continue to participate in LTM each year.</p>
<p>This B2B event has been carefully thought out and planned in such a way that sellers and potential buyers of services – companies working with VIP clients, have the opportunity to establish partnerships within the space of a single day. The main aim of the of the organisers of the Luxury Travel Mart is to expand the geography of sales of the exhibition’s participants and also to introduce players to new areas and services to which they previously did not have access.</p>";
                $result["ADMIN"]["WORK_POSITION"] = "Administrator";
                $result["ADMIN"]["ROLE"] = iconv("CP1251","UTF-8","Administrator");
            }

            $adminmessagesCount = 0;
            $sql = mysql_query("SELECT * FROM `chat_admin_messages` WHERE `AUTHOR_ID` = '" . $USER->GetID() . "' OR `TO_USER` = '" . $USER->GetID() . "' GROUP BY TO_USER  ORDER BY ID DESC", $this->db);
            $adminmessagesCount = mysql_num_rows($sql);
            $result["ADMIN"]["MESSAGES_COUNT"] = $adminmessagesCount;


            $arFilter = array(">=UF_LAT" => $FindLatMin, "<=UF_LAT" => $FindLatMax, ">=UF_LNG" => $FindLngMin, "<=UF_LNG" => $FindLngMax, "!ID"=>$USER->GetID());
            if (CURRENT_USER_GROUP==APP_EXHIBITOR) $arFilter["GROUPS_ID"] = array(APP_BUYER);
            if (CURRENT_USER_GROUP==APP_BUYER) $arFilter["GROUPS_ID"] = array(APP_EXHIBITOR);
            if (!empty($blocked_list)) $arFilter["ID"] = "~".implode(" & ~",$blocked_list);
            $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $arFilter, array("NAV_PARAMS" => array("nPageSize" => $limit, "iNumPage" => $offset), "SELECT"=>array("UF_WORK_COUNTRY","UF_WORK_CITY","UF_ABOUT","UF_WORK_POSITION","UF_PERSONAL_PHOTO","UF_IS_ONLINE","UF_FAVORITES","UF_BLOCK_LIST")));
            //$rsUsers->NavStart($limit);
            while ($arUsers = $rsUsers->getNext()) {

                $is_fav = '';
                unset($is_fav);
                $rsUser = $USER->GetByID($arUsers["ID"]);
                $arUser = $rsUser->Fetch();

                $result["USERS"][$k]["ID"] = $arUser["ID"];
                $result["USERS"][$k]["NAME"] = $arUser["NAME"];
                $result["USERS"][$k]["LAST_NAME"] = $arUser["LAST_NAME"];
                $result["USERS"][$k]["WORK_COMPANY"] = str_replace(array("'",'"',"&quot;"),"",$arUser["WORK_COMPANY"]);

                $result["USERS"][$k]["WORK_COUNTRY"] = $arUser["UF_WORK_COUNTRY"];
                $result["USERS"][$k]["WORK_CITY"] = $arUser["UF_WORK_CITY"];
                $result["USERS"][$k]["ABOUT"] = $arUser["UF_ABOUT"];
                $result["USERS"][$k]["WORK_POSITION"] = $arUser["UF_WORK_POSITION"];
                $result["USERS"][$k]["PERSONAL_PHOTO"] = $arUser["UF_PERSONAL_PHOTO"];

                $role = '';
                unset($pers_foto);
                unset($arrrole);

                $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                foreach ($arGroups as $grp) {
                    if ($grp==APP_BUYER) {
                        $arrrole[] = 'Buyer';
                    }
                    if ($grp==APP_EXHIBITOR) {
                        $arrrole[] = 'Exhibitor';
                    }
                }
                if (!empty($arrrole)) $role = implode(", ", $arrrole);
                $result["USERS"][$k]["ROLE"] = iconv("CP1251","UTF-8",$role);
                if ($arUser["UF_IS_ONLINE"]) $result["USERS"][$k]["IS_ONLINE"] = 1; else $result["USERS"][$k]["IS_ONLINE"] = 0;
                $arFilter2 = array("ID"=>$USER->getId(), "UF_FAVORITES"=>$arUsers["ID"]);
                $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                while($arUsers2 = $rsUsers2->getNext()) {
                    $is_fav = 1;
                }
                if ($is_fav) $result["USERS"][$k]["IS_FAVORITE"] = 1; else $result["USERS"][$k]["IS_FAVORITE"] = 0;

                //GROUP BY TO_USER

                $sql = mysql_query("SELECT * FROM `chat_messages` WHERE (`AUTHOR_ID` = '" . $arUser["ID"] . "' AND `TO_USER` = '" . $USER->GetID() . "') OR (`AUTHOR_ID` = '" . $USER->GetID() . "' AND `TO_USER` = '" . $arUser["ID"] . "')  ORDER BY ID DESC", $this->db);
                $result["USERS"][$k]["MESSAGES_COUNT"] = intval(mysql_num_rows($sql));

                $k++;
            }

            if (is_array($result))
                $this->response($this->json($result), 200);
            else {
                $arrError = array("error_code" => 204, "error_message" => "Empty list");
                $this->response($this->json($arrError), 200);
            }

        } elseif ($this->_request['func']=="feedbackQuestions" AND $this->get_request_method() == "GET") {

            CModule::IncludeModule("vote");

            $db_res = GetVoteDataByID(4, $arChannel, $arVote, $arQuestions, $arAnswers, $arDropDown, $arMultiSelect, $arGroupAnswers, "N");
            $k=0;

            foreach ($arQuestions as $Q) {
                $result[$k]["ID"] = $Q["ID"];
                $result[$k]["QUESTION"] = $Q["QUESTION"];
                foreach ($Q["ANSWERS"] as $answ) {
                    if ($answ["FIELD_TYPE"] == 0) $result[$k]["ANSWERS"][$answ["MESSAGE"]] = $answ["ID"];
                    if ($answ["FIELD_TYPE"] == 4) if ($answ["MESSAGE"]=="Points") $result[$k]["ANSWERS"][$answ["MESSAGE"]] = $answ["ID"]; else $result[$k]["INNER"][$answ["MESSAGE"]] = $answ["ID"];
                }
                $k++;
            }


            if (is_array($result))
                $this->response($this->json($result), 200);
            else
                $this->response('', 200);


        } elseif ($this->_request['func']=="getSettings" AND $this->get_request_method() == "GET") {

            if (intval($this->_request['user_id'])>0) {
                $rsUser = $USER->GetByID($this->_request['user_id']);
                $arUser = $rsUser->Fetch();

                $messagesCount = $blockedCount = 0;

                $block = $arUser["UF_BLOCK_LIST"];
                $blockedCount = count($block);

                $sql = mysql_query("SELECT * FROM `chat_messages` WHERE `AUTHOR_ID` = '" . $this->_request['user_id'] . "' OR `TO_USER` = '" . $this->_request['user_id'] . "' GROUP BY TO_USER  ORDER BY ID DESC", $this->db);
                $messagesCount = mysql_num_rows($sql);

                $sql = mysql_query("SELECT * FROM `chat_admin_messages` WHERE `AUTHOR_ID` = '" . $this->_request['user_id'] . "' OR `TO_USER` = '" . $this->_request['user_id'] . "' GROUP BY TO_USER  ORDER BY ID DESC", $this->db);
                $adminmessagesCount = mysql_num_rows($sql);

                $result["blockedCount"] = $blockedCount;
                $result["messagesCount"] = $messagesCount;
                $result["adminmessagesCount"] = $adminmessagesCount;

                if (is_array($result))
                    $this->response($this->json($result), 200);
                else
                    $this->response('', 200);

            } else {
                $this->response('', 200);
            }

        } elseif ($_REQUEST['func']=="sendFeedback" AND $this->get_request_method() == "POST") {


            /*
                $_REQUSEST["answ"] = Array
                            (
                                "0" => Array
                                (
                                    "6" => 27
                                ),

                                "1" => Array
                            (
                                "7" => 30
                            ),

                            "2" => Array
                            (
                                "8" => 31
                            ),

                            "3" => Array
                            (
                                "10" => 35
                            ),

                            "4" => Array
                            (
                                "11" => 36
                            ),

                            "5" => Array
                            (
                                "12" => 39
                            ),

                            "6" => Array
                            (
                                "13" => 40
                            ),

                            "7" => Array
                            (
                                "9" => 1
                            )
                    );

                            $_REQUSEST["points"] = 7;
                            $_REQUSEST["buyer_id"] = 3441;

            */


            if (intval($this->_request['buyer_id'])>0) {


/*
                $fp = fopen($_SERVER["DOCUMENT_ROOT"] . '/rst/log.txt', 'a');
                $str = date("d.m.Y H:i:s", time()) . " - " . $_SERVER["REMOTE_ADDR"] . " - " . PHP_EOL;
                foreach ($this->_request as $k=>$v) {
                    $str .= $k." - ".$v.PHP_EOL;
                }
                foreach ($this->_request["answ"] as $k=>$v) {
                    foreach ($v as $k2=>$v2) {
                        $str .= "innerE " . $k2 . " - " . $v2 . PHP_EOL;
                        foreach ($v2 as $k3=>$v3) {

                        }
                    }
                }

                fwrite($fp, $str);
                fclose($fp);
*/

                $VOTE_ID = 4;

                mysql_query("INSERT INTO `b_vote_event` (`ID`, `VOTE_ID`, `VOTE_USER_ID`, `DATE_VOTE`, `STAT_SESSION_ID`, `IP`, `VALID`) VALUES (NULL, '".$VOTE_ID."', '".$USER->GetId()."', '".date("Y-m-d H:i:s",time())."', '', '".$_SERVER["REMOTE_ADDR"]."', 'Y')");
                $eventID = mysql_insert_id();

                if ($this->_request["points"]) {
                    mysql_query("INSERT INTO `b_vote_event_question` (`ID`, `EVENT_ID`, `QUESTION_ID`) VALUES (NULL, '" . $eventID . "', '13')");
                    $eventQID = mysql_insert_id();
                    mysql_query("INSERT INTO `b_vote_event_answer` (`ID`, `EVENT_QUESTION_ID`, `ANSWER_ID`, `MESSAGE`) VALUES (NULL, '".$eventQID."', '41', '".$this->_request["points"]."')");
                }
                if ($this->_request["buyer_id"]) {
                    mysql_query("INSERT INTO `b_vote_event_question` (`ID`, `EVENT_ID`, `QUESTION_ID`) VALUES (NULL, '" . $eventID . "', '14')");
                    $eventQID = mysql_insert_id();
                    mysql_query("INSERT INTO `b_vote_event_answer` (`ID`, `EVENT_QUESTION_ID`, `ANSWER_ID`, `MESSAGE`) VALUES (NULL, '".$eventQID."', '42', '".$this->_request["buyer_id"]."')");
                }

                foreach ($this->_request["answ"] as $k=>$v) {
                    foreach ($v as $k2=>$v2) {

                        if (!$k2 OR $k2==0) {
                            if ($k AND $v2) {
                                mysql_query("INSERT INTO `b_vote_event_question` (`ID`, `EVENT_ID`, `QUESTION_ID`) VALUES (NULL, '" . $eventID . "', '" . $k . "')");
                                $eventQID = mysql_insert_id();
                                mysql_query("INSERT INTO `b_vote_event_answer` (`ID`, `EVENT_QUESTION_ID`, `ANSWER_ID`, `MESSAGE`) VALUES (NULL, '" . $eventQID . "', '" . $v2 . "', '')");
                            }
                        } else {
                            if ($k2 AND $v2) {
                                mysql_query("INSERT INTO `b_vote_event_question` (`ID`, `EVENT_ID`, `QUESTION_ID`) VALUES (NULL, '" . $eventID . "', '" . $k2 . "')");
                                $eventQID = mysql_insert_id();
                                mysql_query("INSERT INTO `b_vote_event_answer` (`ID`, `EVENT_QUESTION_ID`, `ANSWER_ID`, `MESSAGE`) VALUES (NULL, '" . $eventQID . "', '" . $v2 . "', '')");
                            }
                        }
                    }
                }



                $DB->StartTransaction();
                $DB->Query(
                    "UPDATE b_vote V SET V.COUNTER=(".
                    "SELECT COUNT(VE.ID) FROM b_vote_event VE WHERE VE.VOTE_ID=V.ID".
                    ") WHERE V.ID=".$VOTE_ID, false, $err_mess.__LINE__);
                $DB->Query(
                    "UPDATE b_vote_question VQ SET VQ.COUNTER=(".
                    "SELECT COUNT(VEQ.ID) FROM b_vote_event_question VEQ WHERE VEQ.QUESTION_ID=VQ.ID".
                    ") WHERE VQ.VOTE_ID=".$VOTE_ID, false, $err_mess.__LINE__);
                $DB->Query(
                    "UPDATE b_vote_answer VA, b_vote_question VQ SET VA.COUNTER=(".
                    " SELECT COUNT(VEA.ID) FROM b_vote_event_answer VEA WHERE VEA.ANSWER_ID=VA.ID".
                    ") WHERE VQ.ID = VA.QUESTION_ID AND VQ.VOTE_ID=".$VOTE_ID, false, $err_mess.__LINE__);
                $DB->Query(
                    "UPDATE b_vote_user VU, b_vote_event VE SET VU.COUNTER=(".
                    " SELECT COUNT(VE.ID) FROM b_vote_event VE WHERE VU.ID=VE.VOTE_USER_ID AND VE.VALID='Y' ".
                    ") WHERE VU.ID IN (SELECT DISTINCT VEE.VOTE_USER_ID FROM b_vote_event VEE WHERE VOTE_ID=".$VOTE_ID.")", false, $err_mess.__LINE__);
                $DB->Commit();

                $result = array('status' => "Success", "msg" => "Your feedback sent to the organisers");
                $this->response($this->json($result), 200);

            } else {
                $result = array('status' => "Error", "msg" => "Feedback form error");
                $this->response($this->json($result), 200);
            }

            /*

              <pre>Array
            (
                [auth_token] => 78011d3b6eb7bd7a4019eb4b85763872
                [answ] => Array
                    (
                        [0] => Array
                            (
                                [6] => 27
                            )

                        [1] => Array
                            (
                                [7] => 30
                            )

                        [2] => Array
                            (
                                [8] => 31
                            )

                        [3] => Array
                            (
                                [10] => 35
                            )

                        [4] => Array
                            (
                                [11] => 36
                            )

                        [5] => Array
                            (
                                [12] => 39
                            )

                        [6] => Array
                            (
                                [13] => 40
                            )

                        [7] => Array
                            (
                                [9] => 1
                            )

                    )

                [buyer_id] => 3441
                [points] => 1
                [call] => user
                [func] => sendFeedback
                [?auth_token] => 78011d3b6eb7bd7a4019eb4b85763872
            )
            </pre>


                            $arFields = array(
                                "QUESTION_ID" => 6,
                                "" => "",
                                "" => "",
                                "" => "",
                            );
                            $ID = CAllVoteAnswer::Add($arFields);
            echo "sdf ".$ID;*/


        } elseif ($_REQUEST['func']=="addToBlockList" AND $this->get_request_method() == "POST") {

            if (intval($this->_request['user_id'])>0) {

                $rsUser = $USER->GetByID($USER->GetID());
                $arUser = $rsUser->Fetch();

                $rsUser2 = $USER->GetByID($this->_request['user_id']);
                $arUser2 = $rsUser2->Fetch();

                $favorites = $arUser["UF_BLOCK_LIST"];
                foreach ($favorites as $fav) {
                    $arrFav[$fav] = $fav;
                }
                $arrFav[$this->_request['user_id']] = $this->_request['user_id'];
                $fields = Array("UF_BLOCK_LIST"=>$arrFav);
                $USER->Update($USER->GetID(), $fields);

                $result = array('status' => "Success", "msg" => "You have blocked ".$arUser2["NAME"]);
                $this->response($this->json($result), 200);

            } else {
                $result = array('status' => "Error", "msg" => "User not found");
                $this->response($this->json($result), 200);
            }

        } elseif ($_REQUEST['func']=="removeFromBlockList" AND $this->get_request_method() == "POST") {


            if (intval($this->_request['user_id'])>0) {

                $rsUser = $USER->GetByID($USER->GetID());
                $arUser = $rsUser->Fetch();

                $arrFav = array();
                $favorites = $arUser["UF_BLOCK_LIST"];
                foreach ($favorites as $fav) {
                    if ($this->_request['user_id']!=$fav) $arrFav[$fav] = $fav;
                }
                $fields = Array("UF_BLOCK_LIST"=>$arrFav);
                $USER->Update($USER->GetID(), $fields);

                $result = array('status' => "Success", "msg" => "User unblocked");
                $this->response($this->json($result), 200);

            } else {
                $result = array('status' => "Error", "msg" => "User not found");
                $this->response($this->json($result), 200);
            }


        } elseif ($_REQUEST['func']=="removeAllFromBlockList" AND $this->get_request_method() == "POST") {

            $fields = Array("UF_BLOCK_LIST"=>array());
            $USER->Update($USER->GetID(), $fields);

            $result = array('status' => "Success", "msg" => "All users unblocked");
            $this->response($this->json($result), 200);


        } elseif ($_REQUEST['func']=="addToFavorite" AND $this->get_request_method() == "POST") {

            if (intval($this->_request['user_id'])>0) {

                $rsUser = $USER->GetByID($USER->GetID());
                $arUser = $rsUser->Fetch();

                $favorites = $arUser["UF_FAVORITES"];
                foreach ($favorites as $fav) {
                    $arrFav[$fav] = $fav;
                }
                $arrFav[$this->_request['user_id']] = $this->_request['user_id'];
                $fields = Array("UF_FAVORITES"=>$arrFav);
                $USER->Update($USER->GetID(), $fields);


                $data = date("Y-m-d H:i:s", time());
                $result = mysql_query("INSERT INTO  `chat_messages` (`ID`,`AUTHOR_ID`,`TO_USER`,`MESSAGE`,`DATE_CREATE`) VALUES (NULL,'".$USER->GetId()."','".$this->_request["user_id"]."','".strip_tags($USER->GetFullName().' added you to favorites list')."','".$data."')", $this->db);
                $ID = mysql_insert_id($this->db);

                $result = array('status' => "Success", "msg" => $arUser["NAME"]." added to favorites");
                $this->response($this->json($result), 200);

            } else {
                $result = array('status' => "Error", "msg" => "User not found");
                $this->response($this->json($result), 200);
            }

        } elseif ($_REQUEST['func']=="removeFromFavorite" AND $this->get_request_method() == "POST") {


            if (intval($this->_request['user_id'])>0) {

                $rsUser = $USER->GetByID($USER->GetID());
                $arUser = $rsUser->Fetch();

                $arrFav = array();
                $favorites = $arUser["UF_FAVORITES"];
                foreach ($favorites as $fav) {
                    if ($this->_request['user_id']!=$fav) $arrFav[$fav] = $fav;
                }

                $fields = Array("UF_FAVORITES"=>$arrFav);
                $USER->Update($USER->GetID(), $fields);

                $result = array('status' => "Success", "msg" => $arUser["NAME"]." removed from favorites");
                $this->response($this->json($result), 200);

            } else {
                $result = array('status' => "Error", "msg" => "User not found");
                $this->response($this->json($result), 200);
            }

        } elseif ($_REQUEST['func']=="checkIn" AND $this->get_request_method() == "POST") {

            if ($this->_request['lat'] AND $this->_request['lng']) {

                $fields = Array("UF_LAT"=>$this->_request['lat'], "UF_LNG"=>$this->_request['lng'], "UF_IS_ONLINE"=>1);
                $USER->Update($USER->GetID(), $fields);

                $result = array('status' => "Success", "msg" => "Check-in successfully done!");
                $this->response($this->json($result), 200);

            } else {
                $result = array('status' => "Error", "msg" => "No position data");
                $this->response($this->json($result), 200);
            }

        } elseif ($_REQUEST['func']=="checkOut" AND $this->get_request_method() == "POST") {

            $fields = Array("UF_IS_ONLINE"=>0);
            $USER->Update($USER->GetID(), $fields);

            $result = array('status' => "Success", "msg" => "Thank you for your participation!");
            $this->response($this->json($result), 200);

        } elseif ($_REQUEST['func']=="ring" AND $this->get_request_method() == "POST") {

            if (intval($this->_request['user_id'])>0) {

                $rsUser = $USER->GetByID($this->_request['user_id']);
                $arUser = $rsUser->Fetch();



                $rsUser2 = $USER->GetByID($this->_request['from_user_id']);
                $arUser2 = $rsUser2->Fetch();

                $data = date("Y-m-d H:i:s", time());
                $result = mysql_query("INSERT INTO  `chat_messages` (`ID`,`AUTHOR_ID`,`TO_USER`,`MESSAGE`,`DATE_CREATE`) VALUES (NULL,'".$USER->GetId()."','".$this->_request["user_id"]."','".strip_tags($USER->GetFullName().' invites you for the next appointment')."','".$data."')", $this->db);
                $ID = mysql_insert_id($this->db);


                $tokens = '';
                foreach ($arUser["UF_PUSH_TOKENS"] as $token) {
                    $tokens .= 'push_tokens[]='.$token.'&';
                }

                $dev_ids = '';
                foreach ($arUser["UF_DEVICE_ID"] as $token) {
                    $dev_ids .= 'device_ids[]='.$token.'&';
                }

                $options = array(
                    'http'=>array(
                        'method'=>"GET",

                        'header'=>"Accept-language: en\r\n" .
                        "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36\r\n"
                    )
                );
                $context = $file = '';
                $context = stream_context_create($options);
                $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_ring.php?user_id='.$this->_request['user_id'].'&'.$tokens.'&'.$dev_ids.'&user_name='.$arUser2["NAME"].'&from_user_id='.CUser::GetFullName(), false, $context);
                $file2 = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_ring_android.php?user_id='.$this->_request['user_id'].'&'.$tokens.'&'.$dev_ids.'&user_name='.$arUser2["NAME"].'&from_user_id='.CUser::GetFullName(), false, $context);

                //if ($_SESSION[CUser::GetID()]["push_status"]==1) $result = array('status' => "Success", "msg" => "Сообщение отправлено");
                //else $result = array('status' => "Error", "msg" => "Ошибка отправки сообщения");



                $result = array('status' => "Success", "msg" => "Your invitation sent to ".$arUser["NAME"]);
                $this->response($this->json($result), 200);

            } else {
                $result = array('status' => "Error", "msg" => "User not found");
                $this->response($this->json($result), 200);
            }

        } elseif ($_REQUEST['func']=="abuse" AND $this->get_request_method() == "POST") {

            if (intval($this->_request['user_id'])>0) {

                $rsUser = $USER->GetByID($this->_request['user_id']);
                $arUser = $rsUser->Fetch();


                $sql = mysql_query("SELECT * FROM `meetings_requests` WHERE `EXHIBITION_ID` ='".EXHIBITION_ID."' AND `SENDER_ID` = '".$USER->GetId()."' AND `RECEIVER_ID`='".$this->_request['user_id']."' ORDER BY TIMESLOT_ID LIMIT 0,1", $this->db);
                if(mysql_num_rows($sql) > 0) {
                    while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
                        $meeting_id = $row["ID"];
                    }
                }

                $data = date("Y-m-d H:i:s", time());
                $result = mysql_query("INSERT INTO  `no_show_reports` (`ID`,`MEETING_ID`,`SENDER_ID`,`RECEIVER_ID`,`REPORT_TIME`) VALUES (NULL,'".$meeting_id."','".$USER->GetId()."','".$this->_request["user_id"]."','".$data."')", $this->db);
                $ID = mysql_insert_id($this->db);

                $tokens = '';
                foreach ($arUser["UF_PUSH_TOKENS"] as $token) {
                    $tokens .= 'push_tokens[]='.$token.'&';
                }
                $dev_ids = '';
                foreach ($arUser["UF_DEVICE_ID"] as $token) {
                    $dev_ids .= 'device_ids[]='.$token.'&';
                }


                $options = array(
                    'http'=>array(
                        'method'=>"GET",

                        'header'=>"Accept-language: en\r\n" .
                        "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36\r\n"
                    )
                );
                $context = $file = '';
                $context = stream_context_create($options);
                $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_abuse.php?user_id='.$this->_request['user_id'].'&'.$tokens.'&'.$dev_ids.'&user_name='.$arUser["NAME"].'&from_user_id='.CUser::GetID(), false, $context);
                $file2 = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_abuse_android.php?user_id='.$this->_request['user_id'].'&'.$tokens.'&'.$dev_ids.'&user_name='.$arUser["NAME"].'&from_user_id='.CUser::GetID(), false, $context);
                //echo $file2;



                //if ($_SESSION[CUser::GetID()]["push_status"]==1) $result = array('status' => "Success", "msg" => "Сообщение отправлено");
                //else $result = array('status' => "Error", "msg" => "Ошибка отправки сообщения");

                $result = array('status' => "Success", "msg" => "Thank you for your report of no-show");
                $this->response($this->json($result), 200);

            } else {
                $result = array('status' => "Error", "msg" => "User not found");
                $this->response($this->json($result), 200);
            }


        } elseif ($this->_request['func']=="logout" AND $this->get_request_method() == "GET") {

            $arrPushes = '';

            $rsUser = $USER->GetByID(CUser::GetID());
            $arUser = $rsUser->Fetch();

            if ($this->_request['push_token']) {
                foreach ($arUser["UF_PUSH_TOKENS"] as $push) {
                    if ($push!=$this->_request['push_token']) {
                        $arrPushes[] = $push;
                    }
                }
                $fields = Array("UF_PUSH_TOKENS" => $arrPushes);
                $USER->Update(CUser::GetID(), $fields);
            }
            if ($this->_request['device_id']) {
                foreach ($arUser["UF_DEVICE_ID"] as $push) {
                    if ($push!=$this->_request['device_id']) {
                        $arrPushes[] = $push;
                    }
                }
                $fields = Array("UF_DEVICE_ID" => $arrPushes);
                $USER->Update(CUser::GetID(), $fields);
            }

            $USER->Logout();
            $result = array('status' => "Success", "msg" => "Successful exit");
            $this->response($this->json($result), 200);

            /*
            if ($USER->Logout()) {
                $result = array('status' => "Success", "msg" => "Successful exit");
                $this->response($this->json($result), 200);
            } else {
                $arrError = array("error_code" => 204, "error_message" => "Exit fail");
                $this->response($this->json($arrError), 200);
            }*/

        } else {

            $this->response($this->json(array("method"=>$this->get_request_method(),"func"=>$this->_request['func'])),400);
        }
    }


    private function shedule() {
        global $USER;
        //global $DB;
        // Cross validation if the request method is GET else it will return "Not Acceptable" status
        if($this->get_request_method() != "GET"){
            $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
            $this->response($this->json($result),406);
        }

        BXClearCache(true, "/rst/");

        if ($this->_request['func']=="getUserShedule" AND $this->get_request_method() == "GET") {

            if (intval($this->_request["SENDER_ID"])>0 AND ($this->_request["offset"]==0 OR !$this->_request["offset"])) {



//OR `RECEIVER_ID` = '".$this->_request["SENDER_ID"]."'
                $k=0;
                $sql = mysql_query("SELECT * FROM `meetings_requests` WHERE `STATUS` = 'confirmed' AND `EXHIBITION_ID` ='".EXHIBITION_ID."' AND (`SENDER_ID` = '".$this->_request["SENDER_ID"]."' OR `RECEIVER_ID` = '".$this->_request["SENDER_ID"]."') ORDER BY TIMESLOT_ID", $this->db);
                if(mysql_num_rows($sql) > 0) {
                    while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {

                        $result[$k]["ID"] = iconv("CP1251","UTF-8",$row["ID"]);

                        $rsUser = $USER->GetByID($row["SENDER_ID"]);
                        $arUser = $rsUser->Fetch();

                        $result[$k]["SENDER"]["ID"] = $arUser["ID"];
                        $result[$k]["SENDER"]["NAME"] = $arUser["NAME"];
                        $result[$k]["SENDER"]["LAST_NAME"] = $arUser["LAST_NAME"];
                        $result[$k]["SENDER"]["WORK_COMPANY"] = str_replace(array("'",'"',"&quot;"),"",$arUser["WORK_COMPANY"]);

                        $result[$k]["SENDER"]["WORK_COUNTRY"] = $arUser["UF_WORK_COUNTRY"];
                        $result[$k]["SENDER"]["WORK_CITY"] = $arUser["UF_WORK_CITY"];
                        $result[$k]["SENDER"]["ABOUT"] = $arUser["UF_ABOUT"];
                        $result[$k]["SENDER"]["WORK_POSITION"] = $arUser["UF_WORK_POSITION"];
                        $result[$k]["SENDER"]["PERSONAL_PHOTO"] = $arUser["UF_PERSONAL_PHOTO"];

                        $role = '';
                        unset($pers_foto);
                        unset($arrrole);

                        $arGroups = CUser::GetUserGroup($arUser["ID"]);
                        foreach ($arGroups as $grp) {
                            if ($grp==APP_BUYER) {
                                $arrrole[] = 'Buyer';
                                $DATE = strtotime(EXBDATE)+86400;
                            }
                            if ($grp==APP_EXHIBITOR) {
                                $arrrole[] = 'Exhibitor';
                                $DATE = strtotime(EXBDATE);
                            }
                        }
                        if (!empty($arrrole)) $role = implode(", ", $arrrole);
                        $result[$k]["SENDER"]["ROLE"] = iconv("CP1251","UTF-8",$role);

                        if ($arUser["UF_IS_ONLINE"]) $result[$k]["SENDER"]["IS_ONLINE"] = 1; else $result[$k]["SENDER"]["IS_ONLINE"] = 0;

                        unset($is_fav);
                        $arFilter2 = array("ID"=>$this->_request["SENDER_ID"], "UF_FAVORITES"=>$arUser["ID"]);
                        $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                        while($arUsers2 = $rsUsers2->getNext()) {
                            $is_fav = 1;
                        }
                        if ($is_fav) $result[$k]["SENDER"]["IS_FAVORITE"] = 1; else $result[$k]["SENDER"]["IS_FAVORITE"] = 0;


                        $rsUser = $USER->GetByID($row["RECEIVER_ID"]);
                        $arUser = $rsUser->Fetch();

                        $result[$k]["RECEIVER"]["ID"] = $arUser["ID"];
                        $result[$k]["RECEIVER"]["NAME"] = $arUser["NAME"];
                        $result[$k]["RECEIVER"]["LAST_NAME"] = $arUser["LAST_NAME"];
                        $result[$k]["RECEIVER"]["WORK_COMPANY"] = str_replace(array("'",'"',"&quot;"),"",$arUser["WORK_COMPANY"]);


                        $result[$k]["RECEIVER"]["WORK_COUNTRY"] = $arUser["UF_WORK_COUNTRY"];
                        $result[$k]["RECEIVER"]["WORK_CITY"] = $arUser["UF_WORK_CITY"];
                        $result[$k]["RECEIVER"]["ABOUT"] = $arUser["UF_ABOUT"];
                        $result[$k]["RECEIVER"]["WORK_POSITION"] = $arUser["UF_WORK_POSITION"];
                        $result[$k]["RECEIVER"]["PERSONAL_PHOTO"] = $arUser["UF_PERSONAL_PHOTO"];

                        unset($pers_foto);
                        unset($arrrole);
                        $role = '';
                        $arGroups = CUser::GetUserGroup($arUser["ID"]);
                        foreach ($arGroups as $grp) {
                            if ($grp==APP_BUYER) {
                                $arrrole[] = 'Buyer';
                                $DATE = strtotime(EXBDATE);
                            }
                            if ($grp==APP_EXHIBITOR) {
                                $arrrole[] = 'Exhibitor';
                                $DATE = strtotime(EXBDATE)+86400;
                            }
                        }
                        if (!empty($arrrole)) $role = implode(", ", $arrrole);
                        $result[$k]["RECEIVER"]["ROLE"] = iconv("CP1251","UTF-8",$role);

                        if ($arUser["UF_IS_ONLINE"]) $result[$k]["RECEIVER"]["IS_ONLINE"] = 1; else $result[$k]["RECEIVER"]["IS_ONLINE"] = 0;

                        unset($is_fav);
                        $arFilter2 = array("ID"=>$this->_request["SENDER_ID"], "UF_FAVORITES"=>$arUser["ID"]);
                        $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);

                        while($arUsers2 = $rsUsers2->getNext()) {
                            $is_fav = 1;
                        }
                        if ($is_fav) $result[$k]["RECEIVER"]["IS_FAVORITE"] = 1; else $result[$k]["RECEIVER"]["IS_FAVORITE"] = 0;



                            $sql2 = mysql_query("SELECT * FROM `meetings_timeslots` WHERE `ID` = '".$row["TIMESLOT_ID"]."' AND `EXHIBITION_ID` = '".EXHIBITION_ID."'  ", $this->db);
                            while ($row2 = mysql_fetch_array($sql2, MYSQL_ASSOC)) {

                                $from = floor($row2["TIME_FROM"]/100)*100;
                                $fromDelta = $row2["TIME_FROM"] - $from;

                                $to = floor($row2["TIME_TO"]/100)*100;
                                $toDelta = $row2["TIME_TO"] - $to;

                                $result[$k]["TIME-FROM"] = ($from + $fromDelta*60);
                                $result[$k]["TIME-TO"] = ($to + $toDelta*60);


                            }
                        /*$result["CREATED_AT"] = iconv("CP1251","UTF-8",$row["CREATED_AT"]);
                        $result["UPDATED_AT"] = iconv("CP1251","UTF-8",$row["UPDATED_AT"]);
                        $result["MODIFIED_BY"] = iconv("CP1251","UTF-8",$row["MODIFIED_BY"]);*/
                        $result[$k]["STATUS"] = $row["STATUS"];
                        $result[$k]["EXHIBITION_ID"] = $row["EXHIBITION_ID"];
                        $result[$k]["DATE"] = $DATE;


                        $k++;
                    }

                    unset($arTmp);
                    $arTmp = array();
                    foreach ($result as $res) {
                        $arTmp[$res["TIME-FROM"]] = $res;
                    }
                    ksort($arTmp);
                    unset($result);
                    $result = array();
                    $k=0;
                    foreach ($arTmp as $rs) {
                        $result[$k] = $rs;
                        $k++;
                    }


                    ///if (!empty($result)) {
                    $this->response($this->json($result), 200);

                } else {
                    $arrError = array("error_code" => 204, "error_message" => "Meetings list is empty");
                    $this->response($this->json($arrError), 200);
                }

            } else {
                $this->response($this->json('Wrong request parameters'),400);
            }
/*
        } elseif ($this->_request['func']=="postUserShedule" AND $this->get_request_method() == "POST") {

            if (intval($this->_request["SENDER_ID"])>0 AND $this->_request["RECEIVER_ID"] AND intval($this->_request["EXHIBITION_ID"])>0 AND intval($this->_request["TIME-FROM"])>0 AND intval($this->_request["TIME-TO"])>0) {
                $result = mysql_query("INSERT INTO `ltm_schedule` (`ID`, `SENDER_ID`, `RECEIVER_ID`, `CREATED_AT`, `UPDATED_AT`, `MODIFIED_BY`, `STATUS`, `EXHIBITION_ID`, `TIME-FROM`, `TIME-TO`) VALUES (NULL, '".$this->_request["SENDER_ID"]."', '".$this->_request["RECEIVER_ID"]."', '".date("Y-m-d H:i:s",time())."', '".date("Y-m-d H:i:s",time())."', '".$this->_request["SENDER_ID"]."', 'process', '".$this->_request["EXHIBITION_ID"]."', '".$this->_request["TIME-FROM"]."', '".$this->_request["TIME-TO"]."')", $this->db);
                if (intval($result)>0)
                    $this->response($this->json($result), 200);
                else {
                        $arrError = array("error_code" => 204, "error_message" => "Meetings list is empty");
                        $this->response($this->json($arrError), 200);
                }
            } else {
                $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
                $this->response($this->json($result),400);
            }

        } elseif ($this->_request['func']=="postSheduleStatusSender" AND $this->get_request_method() == "POST") {

            if (intval($this->_request["SENDER_ID"])>0 AND $this->_request["ID"] AND intval($this->_request["STATUS"])>0 ) {
                $result = mysql_query("UPDATE `ltm_schedule` SET `STATUS`='".$this->_request["STATUS"]."' WHERE `ID`='".$this->_request["ID"]."' AND `SENDER_ID`='".$this->_request["SENDER_ID"]."' ", $this->db);
                if (intval($result)>0)
                    $this->response($this->json($result), 200);
                else {
                    $arrError = array("error_code" => 204, "error_message" => "Meetings list is empty");
                    $this->response($this->json($arrError), 200);
                }
            } else {
                $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
                $this->response($this->json($result),400);
            }
        } elseif ($this->_request['func']=="postSheduleStatusReceiver" AND $this->get_request_method() == "POST") {

            if (intval($this->_request["RECEIVER_ID"])>0 AND $this->_request["ID"] AND intval($this->_request["STATUS"])>0 ) {
                $result = mysql_query("UPDATE `ltm_schedule` SET `STATUS`='".$this->_request["STATUS"]."' WHERE `ID`='".$this->_request["ID"]."' AND `RECEIVER_ID`='".$this->_request["RECEIVER_ID"]."' ", $this->db);
                if (intval($result)>0)
                    $this->response($this->json($result), 200);
                else {
                    $arrError = array("error_code" => 204, "error_message" => "Meetings list is empty");
                    $this->response($this->json($arrError), 200);
                }
            } else {
                $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
                $this->response($this->json($result),400);
            }
*/

        } else {
            $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
            $this->response($this->json($result),400);
        }

    }

    private function chat(){
        global $USER;
        // Cross validation if the request method is GET else it will return "Not Acceptable" status
        if($this->get_request_method() != "GET" AND $this->get_request_method() != "POST"){
            $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
            $this->response($this->json($result),400);
        }


        if ($this->_request['func']=="getMessage" AND $this->get_request_method() == "GET") {
            if (intval($this->_request["ID"]) > 0 AND intval($this->_request["AUTHOR_ID"]) > 0 AND intval($this->_request["TO_USER"]) > 0) {

                $sql = mysql_query("SELECT * FROM `chat_messages` WHERE `ID` = '" . $this->_request["ID"] . "' AND `AUTHOR_ID` = '" . $this->_request["AUTHOR_ID"] . "' AND TO_USER = '" . $this->_request["TO_USER"] . "' ORDER BY ID DESC", $this->db);
                if (mysql_num_rows($sql) > 0) {
                    while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
                        $result["ID"] = iconv("CP1251", "UTF-8", $row["ID"]);
                        $result["MESSAGE"] = iconv("CP1251", "UTF-8", $row["MESSAGE"]);
                        $result["DATE_CREATE"] = iconv("CP1251", "UTF-8", $row["DATE_CREATE"]);
                    }
                    $this->response($this->json($result), 200);

                } else {
                    $arrError = array("error_code" => 204, "error_message" => "Chat list free");
                    $this->response($this->json($arrError), 200);
                }

            } else {
                $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
                $this->response($this->json($result),400);
            }
        } elseif ($this->_request['func']=="getAdminMessage" AND $this->get_request_method() == "GET") {
            if (intval($this->_request["ID"])>0 AND intval($this->_request["AUTHOR_ID"])>0 AND intval($this->_request["TO_USER"])>0) {

                $sql = mysql_query("SELECT * FROM `chat_admin_messages` WHERE `ID` = '".$this->_request["ID"]."' AND `AUTHOR_ID` = '".$this->_request["AUTHOR_ID"]."' AND TO_USER = '".$this->_request["TO_USER"]."' ORDER BY ID DESC", $this->db);
                if(mysql_num_rows($sql) > 0) {
                    while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
                        $result["ID"] = iconv("CP1251","UTF-8",$row["ID"]);
                        $result["MESSAGE"] = iconv("CP1251","UTF-8",$row["MESSAGE"]);
                        $result["DATE_CREATE"] = iconv("CP1251","UTF-8",$row["DATE_CREATE"]);
                    }
                    $this->response($this->json($result), 200);

                } else {
                    $arrError = array("error_code" => 204, "error_message" => "Chat list free");
                    $this->response($this->json($arrError), 200);
                }

            } else {
                $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
                $this->response($this->json($result),400);
            }

        } elseif ($this->_request['func']=="getMessages" AND $this->get_request_method() == "GET") {
            if (intval($this->_request["AUTHOR_ID"])>0 AND intval($this->_request["TO_USER"])>0) {

                $offset = 0;
                $limit = 20;
                if (intval($this->_request["limit"])>0) $limit = intval($this->_request["limit"]);
                if (intval($this->_request["offset"])>0) $offset = (intval($this->_request["offset"])-1)*$limit;

                $sql = mysql_query("SELECT * FROM `chat_messages` WHERE (AUTHOR_ID = '".$this->_request["AUTHOR_ID"]."' AND TO_USER = '".$this->_request["TO_USER"]."') OR ( TO_USER = '".$this->_request["AUTHOR_ID"]."' AND AUTHOR_ID = '".$this->_request["TO_USER"]."') ORDER BY ID DESC LIMIT ".$offset.",".$limit, $this->db);
                if(mysql_num_rows($sql) > 0) {
                    $k=0;
                    while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
                        $result[$k]["ID"] = $row["ID"];
                        $result[$k]["AUTHOR_ID"] = $row["AUTHOR_ID"];
                        $result[$k]["MESSAGE"] = $row["MESSAGE"];
                        $result[$k]["DATE_CREATE"] = $row["DATE_CREATE"];
                        $k++;
                    }
                    $this->response($this->json($result), 200);

                } else {
                    $arrError = array("error_code" => 204, "error_message" => "Chat list free");
                    $this->response($this->json($arrError), 200);
                }


            } else {
                $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
                $this->response($this->json($result),400);
            }

        } elseif ($this->_request['func']=="getAdminMessages" AND $this->get_request_method() == "GET") {
            if (intval($this->_request["TO_USER"])>0) {

                $offset = 0;
                $limit = 20;
                if (intval($this->_request["limit"])>0) $limit = intval($this->_request["limit"]);
                if (intval($this->_request["offset"])>0) $offset = (intval($this->_request["offset"])-1)*$limit;

                $arFilter = array("GROUPS_ID"=>array(54));
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                $rsUsers->NavStart($limit);
                while($arUsers = $rsUsers->getNext()) {
                    $admin_id = $arUsers["ID"];
                }
                $result["ADMIN_ID"] = $admin_id;

                $sql = mysql_query("SELECT * FROM `chat_admin_messages` WHERE (AUTHOR_ID = '".$admin_id."' AND TO_USER = '".$this->_request["TO_USER"]."') OR ( TO_USER = '".$admin_id."' AND AUTHOR_ID = '".$this->_request["TO_USER"]."') ORDER BY ID DESC LIMIT ".$offset.",".$limit, $this->db);
                if(mysql_num_rows($sql) > 0) {
                    $k=0;
                    while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
                        $result["MESSAGES"][$k]["ID"] = $row["ID"];
                        $result["MESSAGES"][$k]["AUTHOR_ID"] = $row["AUTHOR_ID"];
                        $result["MESSAGES"][$k]["MESSAGE"] = $row["MESSAGE"];
                        $result["MESSAGES"][$k]["DATE_CREATE"] = $row["DATE_CREATE"];
                        $k++;
                    }
                    $this->response($this->json($result), 200);

                } else {
                    $result["ADMIN_ID"] = $admin_id;
                    $result["MESSAGES"] = array();
                    $this->response($this->json($result), 200);
                }


            } else {
                $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
                $this->response($this->json($result),400);
            }

        } elseif ($this->_request['func']=="getChats" AND $this->get_request_method() == "GET") {

            //AND AUTHOR_ID != TO_USER
            $sql = mysql_query("SELECT * FROM `chat_messages` WHERE (AUTHOR_ID = '" . $USER->GetId() . "' OR TO_USER = '" . $USER->GetId() . "')  GROUP BY TO_USER ORDER BY ID DESC ", $this->db);
            if (mysql_num_rows($sql) > 0)  {
                $k=0;
                while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {

                    $this_admin = '';
                    $is_fav = '';
                    $rsUser = $USER->GetByID($row["TO_USER"]);
                    $arUser = $rsUser->Fetch();

                    $arGroups = CUser::GetUserGroup($arUser["ID"]);
                    foreach ($arGroups as $grp) {
                        if ($grp==54) $this_admin = 1;
                    }

                    if (!$this_admin AND $arUser["ID"]!=$USER->GetID()) {


                        $result[$k]["ID"] = $arUser["ID"];
                        $result[$k]["NAME"] = $arUser["NAME"];
                        $result[$k]["LAST_NAME"] = $arUser["LAST_NAME"];
                        $result[$k]["WORK_COMPANY"] = str_replace(array("'",'"',"&quot;"),"",$arUser["WORK_COMPANY"]);

                        $result[$k]["WORK_COUNTRY"] = $arUser["UF_WORK_COUNTRY"];
                        $result[$k]["WORK_CITY"] = $arUser["UF_WORK_CITY"];
                        $result[$k]["ABOUT"] = $arUser["UF_ABOUT"];
                        $result[$k]["WORK_POSITION"] = $arUser["UF_WORK_POSITION"];
                        $result[$k]["PERSONAL_PHOTO"] = $arUser["UF_PERSONAL_PHOTO"];

                        $role = '';
                        unset($pers_foto);
                        unset($arrrole);

                        foreach ($arGroups as $grp) {
                            if ($grp == APP_BUYER) {
                                $arrrole[] = 'Buyer';
                            }
                            if ($grp == APP_EXHIBITOR) {
                                $arrrole[] = 'Exhibitor';
                            }
                        }

                        if (!empty($arrrole)) $role = implode(", ", $arrrole);
                        $result[$k]["ROLE"] = iconv("CP1251", "UTF-8", $role);
                        if ($arUser["UF_IS_ONLINE"]) $result[$k]["IS_ONLINE"] = 1; else $result[$k]["IS_ONLINE"] = 0;
                        $arFilter2 = array("ID" => $row["SENDER_ID"], "UF_FAVORITES" => $arUsers["ID"]);
                        $rsUsers2 = CUser::GetList(($by = "id"), ($order = "desc"), $arFilter2);
                        while ($arUsers2 = $rsUsers2->getNext()) {
                            $is_fav = 1;
                        }

                        $last_date = $last_mess = '';
                        $sql2 = mysql_query("SELECT * FROM `chat_messages` WHERE (AUTHOR_ID = '" . $USER->GetId() . "' AND TO_USER = '" . $row["TO_USER"] . "') OR (AUTHOR_ID = '" . $row["TO_USER"] . "' AND TO_USER = '" . $USER->GetId() . "') ORDER BY ID DESC LIMIT 0,1 ", $this->db);
                        while ($row2 = mysql_fetch_array($sql2, MYSQL_ASSOC)) {
                            $last_date = $row2["DATE_CREATE"];
                            $last_mess = $row2["MESSAGE"];
                        }

                        $result[$k]["LAST_MESSAGE"]["DATE_CREATE"] = $last_date;
                        $result[$k]["LAST_MESSAGE"]["MESSAGE"] = $last_mess;


                        $k++;
                    }
                }

                $this->response($this->json($result), 200);

            } else {
                $arrError = array("error_code" => 204, "error_message" => "Chat list free");
                $this->response($this->json($arrError), 200);
            }


        } elseif ($_REQUEST['func']=="postMessage" AND $this->get_request_method() == "POST") {

            $this->_request["AUTHOR_ID"] = $USER->GetId();

            if (intval($this->_request["AUTHOR_ID"])>0 AND $this->_request["MESSAGE"] AND intval($this->_request["TO_USER"])>0) {
                $data = date("Y-m-d H:i:s", time());
                $result = mysql_query("INSERT INTO  `chat_messages` (`ID`,`AUTHOR_ID`,`TO_USER`,`MESSAGE`,`DATE_CREATE`) VALUES (NULL,'".$this->_request["AUTHOR_ID"]."','".$this->_request["TO_USER"]."','".strip_tags($this->_request["MESSAGE"])."','".$data."')", $this->db);
                $ID = mysql_insert_id($this->db);


                if (intval($result)>0) {
                    $arResult = array("ID"=>$ID, "AUTHOR_ID"=>$this->_request["AUTHOR_ID"], "MESSAGE"=>$this->_request["MESSAGE"], "DATE_CREATE"=>$data);


                    $rsUser = $USER->GetByID($this->_request["TO_USER"]);
                    $arUser = $rsUser->Fetch();

                    $rsUser2 = $USER->GetByID($this->_request["AUTHOR_ID"]);
                    $arUser2 = $rsUser2->Fetch();

                    $tokens = '';
                    foreach ($arUser["UF_PUSH_TOKENS"] as $token) {
                        $tokens .= 'push_tokens[]='.$token.'&';
                    }
                    $dev_ids = '';
                    foreach ($arUser["UF_DEVICE_ID"] as $token) {
                        $dev_ids .= 'device_ids[]='.$token.'&';
                    }

                    $options = array(
                        'http'=>array(
                            'method'=>"GET",

                            'header'=>"Accept-language: en\r\n" .
                            "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36\r\n"
                        )
                    );
                    $context = $file = '';
                    $context = stream_context_create($options);
                    $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess.php?user_id='.$this->_request["AUTHOR_ID"].'&user_name='.$arUser["NAME"].'&alert='.urlencode(substr($this->_request["MESSAGE"],0,70)).'&from_user_id='.$this->_request["AUTHOR_ID"].'&'.$tokens.'&'.$dev_ids, false, $context);
                    $file2 = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess_android.php?user_id='.$this->_request["AUTHOR_ID"].'&user_name='.$arUser["NAME"].'&alert='.urlencode(substr($this->_request["MESSAGE"],0,70)).'&from_user_id='.$this->_request["AUTHOR_ID"].'&'.$tokens.'&'.$dev_ids, false, $context);
                    //echo $file;


                    $this->response($this->json($arResult), 200);
                } else {
                    $arrError = array("error_code" => 204, "error_message" => "Chat list free");
                    $this->response($this->json($arrError), 200);
                }
            } else {
                $arrError = array("error_code" => 400, "error_message" => "Required items are not filled");
                $this->response($this->json($arrError), 200);
            }

        } elseif ($_REQUEST['func']=="postAdminMessage" AND $this->get_request_method() == "POST") {

            $this->_request["AUTHOR_ID"] = $USER->GetId();

            if (intval($this->_request["AUTHOR_ID"])>0 AND $this->_request["MESSAGE"] AND intval($this->_request["TO_USER"])>0) {
                $data = date("Y-m-d H:i:s", time());
                $result = mysql_query("INSERT INTO  `chat_admin_messages` (`ID`,`AUTHOR_ID`,`TO_USER`,`MESSAGE`,`DATE_CREATE`) VALUES (NULL,'".$this->_request["AUTHOR_ID"]."','".$this->_request["TO_USER"]."','".strip_tags($this->_request["MESSAGE"])."','".$data."') ", $this->db);
                $ID = mysql_insert_id($this->db);


                if (intval($result)>0) {
                    $arResult = array("ID"=>$ID, "AUTHOR_ID"=>$this->_request["AUTHOR_ID"], "MESSAGE"=>$this->_request["MESSAGE"], "DATE_CREATE"=>$data);


                    $rsUser = $USER->GetByID($this->_request["TO_USER"]);
                    $arUser = $rsUser->Fetch();

                    $rsUser2 = $USER->GetByID($this->_request["AUTHOR_ID"]);
                    $arUser2 = $rsUser2->Fetch();

                    $tokens = '';
                    foreach ($arUser["UF_PUSH_TOKENS"] as $token) {
                        $tokens .= 'push_tokens[]='.$token.'&';
                    }

                    $options = array(
                        'http'=>array(
                            'method'=>"GET",

                            'header'=>"Accept-language: en\r\n" .
                            "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36\r\n"
                        )
                    );
                    $context = $file = '';
                    $context = stream_context_create($options);
                    $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess.php?user_id='.$this->_request["AUTHOR_ID"].'&user_name='.$arUser["NAME"].'&alert='.urlencode(substr($this->_request["MESSAGE"],0,70)).'&from_user_id='.$this->_request["AUTHOR_ID"].'&'.$tokens, false, $context);
                    $file2 = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess_android.php?user_id='.$this->_request["AUTHOR_ID"].'&user_name='.$arUser["NAME"].'&alert='.urlencode(substr($this->_request["MESSAGE"],0,70)).'&from_user_id='.$this->_request["AUTHOR_ID"].'&'.$tokens, false, $context);
                    //echo $file;





                    $this->response($this->json($arResult), 200);
                } else {
                    $arrError = array("error_code" => 204, "error_message" => "Chat list free");
                    $this->response($this->json($arrError), 200);
                }
            } else {
                $arrError = array("error_code" => 400, "error_message" => "Required items are not filled");
                $this->response($this->json($arrError), 200);
            }
        } else {
            $result = array('status' => "Error", "msg" => "Invalid request");
            $this->response($this->json($result),400);
        }


    }

    /*
    private function updateUser() {
        global $USER;
        if($this->get_request_method() != "GET" AND $this->get_request_method() != "POST"){
            $this->response('',406);
        }


        if ($this->_request['func']=="getMessage" AND $this->get_request_method() == "GET") {

        } else {
            $this->response('',400);
        }
    }
    */


    private function deleteUser(){
        global $USER;
        // Cross validation if the request method is DELETE else it will return "Not Acceptable" status
        if($this->get_request_method() != "DELETE"){
            $result = array('status' => "Error", "msg" => "Invalid (parameter) request");
            $this->response($this->json($result),400);
        }
        $id = (int)$this->_request['id'];
        if($id > 0){
            mysql_query("DELETE FROM users WHERE user_id = $id");
            $success = array('status' => "Success", "msg" => "Successfully one record deleted.");
            $this->response($this->json($success),200);
        }else
            $this->response('',204);	// If no records "No Content" status
    }

    /*
     *	Encode array into JSON
    */
    private function json($data){
        if(is_array($data)){
            return json_encode($data);
        }
    }
}

// Initiiate Library

$api = new API;
$api->processApi();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
