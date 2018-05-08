<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('form');

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
      `user_email` varchar(50) NOT NULL,
      `user_password` varchar(50) NOT NULL,
      `user_status` tinyint(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 */

require_once("Rest.inc.php");

class API extends REST {

    public $data = "";
    const DB_SERVER = "localhost";
    const DB_USER = "dev";
    const DB_PASSWORD = "";
    const DB = "dev";

    private $db = NULL;


    public function __construct(){
        parent::__construct();
        $this->dbConnect();
    }

    /*
     *  Database connection
    */
    private function dbConnect(){
        $this->db = mysqli_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
        if($this->db)
            mysqli_select_db(self::DB,$this->db);
    }

    /*
     * Public method for access api.
     * This method dynmically call the method based on the query string
     *
     */
    public function processApi()
    {
        global $USER;
        $user = new User;

        $fp = fopen($_SERVER["DOCUMENT_ROOT"] . '/rst/log.txt', 'a');
        $str = date("d.m.Y H:i:s", time()) . " - " . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER["REQUEST_URI"] . PHP_EOL;
        fwrite($fp, $str);
        fclose($fp);


        if (!$user->IsAuthorized() AND !$_REQUEST["auth_token"]) {
            $arrError = array("error_code" => 401, "error_message" => "User not authenticated");
            $this->response($this->json($arrError), 200);
        } elseif ($_REQUEST["auth_token"]) {

            $filter = array(
                "UF_AUTH_TOKEN" => $_REQUEST["auth_token"],
                //"UF_AUTH_TOKEN_EXACT_MATCH" => "Y"
            );
            $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $filter, array("SELECT" => array("UF_*")));
            $rsUsers->NavStart(10);
            while ($arUsers = $rsUsers->getNext()) {
                $user_id = $arUsers["ID"];

            }

            if (!$user->IsAuthorized()) {
                if (intval($user_id) > 0) $user->Authorize($user_id);
            }
        } else {
            $arrError = array("error_code" => 401, "error_message" => "User not authenticated");
            $this->response($this->json($arrError), 200);
        }


        if (!$user->GetId()) {
            $arrError = array("error_code" => 401, "error_message" => "User not authenticated");
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
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                while($arUsers = $rsUsers->getNext()) {
                    $result["ID"] = $arUsers["ID"];
                    $result["NAME"] = $arUsers["NAME"];
                    $result["LAST_NAME"] = $arUsers["LAST_NAME"];
                    $result["WORK_COMPANY"] = $arUsers["WORK_COMPANY"];
                    //$result["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arUsers["PERSONAL_PHOTO"])."&w=186&h=186&zc=4";
                    $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166"));

                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if ($grp==52) {
                            $arrrole[] = 'Buyer';
                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "	SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391"));

                            if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                $result["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                            else
                                $result["PERSONAL_PHOTO"] = '';

                            $result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                            $result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                            $result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                            $result["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"];
                        }
                        if ($grp==50) {
                            $arrrole[] = 'Exhibitor';
                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_772","SIMPLE_QUESTION_652"));
                            //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                            //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];


                            if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                $result["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                            else
                                $result["PERSONAL_PHOTO"] = '';
                            //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];

                            $result["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];

                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                            $result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                            $result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                            $result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"];
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
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter, array("SELECT"=>array("UF_*")));
                while($arUsers = $rsUsers->getNext()) {
                    $result["ID"] = $arUsers["ID"];
                    $result["NAME"] = $arUsers["NAME"];//$arUsers["NAME"];
                    $result["LAST_NAME"] = $arUsers["LAST_NAME"];//$arUsers["LAST_NAME"];
                    $result["WORK_COMPANY"] = $arUsers["WORK_COMPANY"];//$arUsers["WORK_COMPANY"];
                    //$result["PERSONAL_COUNTRY"] = $arUsers["PERSONAL_COUNTRY"];//$arUsers["PERSONAL_COUNTRY"];
                    //$result["WORK_COUNTRY"] = $arUsers["WORK_COUNTRY"];//$arUsers["WORK_COUNTRY"];
                    //$arUsers["PERSONAL_PHOTO"];
                    $role = '';

                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if ($grp==52) {
                            $arrrole[] = 'Buyer';
                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "	SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391"));

                            if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                $result["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                            else
                                $result["PERSONAL_PHOTO"] = '';

                            $result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                            $result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                            $result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                            $result["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"];
                        }
                        if ($grp==50) {
                            $arrrole[] = 'Exhibitor';
                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_772","SIMPLE_QUESTION_652"));
                            //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                            //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                            if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                $result["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                            else
                                $result["PERSONAL_PHOTO"] = '';
                            //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                            $result["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];


                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                            $result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                            $result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                            $result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"];

                        }
                        if ($grp==54) {
                            $arrrole[] = 'Administrator';
                        }
                    }

                    //"http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arUsers["PERSONAL_PHOTO"])."&w=1000&h=1000&zc=4";//$arUsers["PERSONAL_PHOTO"];
                    //$result["WORK_POSITION"] = $arUsers["PERSONAL_PROFESSION"];//$arUsers["PERSONAL_PROFESSION"];

                    if (!empty($arrrole)) $role = implode(", ", $arrrole);
                    $result["ROLE"] = iconv("CP1251","UTF-8",$role);

                    if ($arUsers["UF_IS_ONLINE"]) $result["IS_ONLINE"] = 1; else $result["IS_ONLINE"] = 0;

                    $arFilter2 = array("ID"=>CUser::GetID(), "UF_FAVORITES"=>$arUsers["ID"]);
                    $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                    while($arUsers2 = $rsUsers2->getNext()) {
                        $is_fav = 1;
                    }

                    $sql = mysqli_query("SELECT * FROM `chat_messages` WHERE (`AUTHOR_ID` = '" . $arUsers["ID"] . "' AND `TO_USER` = '" . $USER->GetID() . "') OR (`AUTHOR_ID` = '" . $USER->GetID() . "' AND `TO_USER` = '" . $arUsers["ID"] . "') ", $this->db);
                    $result["MESSAGES_COUNT"] = mysqli_num_rows($sql);

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
                $this->response($this->json($result),400);
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

            $arFilter = array("GROUPS_ID"=>array(50));
            if (!empty($blocked_list)) $arFilter["ID"] = "~".implode(" & ~",$blocked_list);

            $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter, array("NAV_PARAMS"=>array("nPageSize"=>$limit,"iNumPage"=>$offset), "SELECT"=>array("UF_*")));
            $rsUsers->NavStart($limit);
            while($arUsers = $rsUsers->getNext()) {
                $is_fav='';

                $result[$k]["ID"] = $arUsers["ID"];
                $result[$k]["NAME"] = $arUsers["NAME"];
                $result[$k]["LAST_NAME"] = $arUsers["LAST_NAME"];
                $result[$k]["WORK_COMPANY"] = $arUsers["WORK_COMPANY"];
                $role = '';

                $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                foreach ($arGroups as $grp) {
                    if ($grp==52) {
                        $arrrole[] = 'Buyer';
                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "	SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391"));

                        if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                            $result[$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                        else
                            $result[$k]["PERSONAL_PHOTO"] = '';

                        $result[$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                        $result[$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                        $result[$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                        $result[$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"];
                    }
                    if ($grp==50) {
                        $arrrole[] = 'Exhibitor';
                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_772","SIMPLE_QUESTION_652"));
                        //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                        //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];


                        if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                            $result[$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                        else
                            $result[$k]["PERSONAL_PHOTO"] = '';
                        //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];

                        $result[$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];

                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                        $result[$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                        $result[$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                        $result[$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"];
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

            $rsUserMe = $USER->GetByID(CUser::GetID());
            $arUserMe = $rsUserMe->Fetch();
            $blocked_list = $arUserMe["UF_BLOCK_LIST"];

            $limit = 20;
            $offset = 1;
            if (intval($this->_request["limit"])>0) $limit = intval($this->_request["limit"]);
            if (intval($this->_request["offset"])>0) $offset = intval($this->_request["offset"]);
            $k=0;
            $arFilter = array( "GROUPS_ID"=>array(52));
            if (!empty($blocked_list)) $arFilter["ID"] = "~".implode(" & ~",$blocked_list);

            $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter, array("NAV_PARAMS"=>array("nPageSize"=>$limit,"iNumPage"=>$offset), "SELECT"=>array("UF_*")));
            //$rsUsers->NavStart($limit);
            while($arUsers = $rsUsers->getNext()) {
                $is_fav = '';
                $result[$k]["ID"] = $arUsers["ID"];
                $result[$k]["NAME"] = $arUsers["NAME"];
                $result[$k]["LAST_NAME"] = $arUsers["LAST_NAME"];
                $result[$k]["WORK_COMPANY"] = $arUsers["WORK_COMPANY"];


                $role = '';
                $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                foreach ($arGroups as $grp) {
                    if ($grp==52) {
                        $arrrole[] = 'Buyer';
                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "	SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391"));

                        if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                            $result[$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                        else
                            $result[$k]["PERSONAL_PHOTO"] = '';


                        $result[$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                        $result[$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                        $result[$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                        $result[$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"];
                    }
                    if ($grp==50) {
                        $arrrole[] = 'Exhibitor';
                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_772","SIMPLE_QUESTION_652"));
                        //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                        //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];

                        if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                            $result[$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                        else
                            $result[$k]["PERSONAL_PHOTO"] = '';
                        //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                        $result[$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];

                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                        $result[$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                        $result[$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                        $result[$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"];
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
            }
*/

        } elseif ($this->_request['func']=="getFavorites" AND $this->get_request_method() == "GET") {

            $rsUserMe = $USER->GetByID($USER->GetID());
            $arUserMe = $rsUserMe->Fetch();

            $k=0;
            foreach ($arUserMe["UF_FAVORITES"] as $fav) {

                $is_fav = '';
                $rsUser = $USER->GetByID($fav);
                $arUser = $rsUser->Fetch();

                $result[$k]["ID"] = $arUser["ID"];
                $result[$k]["NAME"] = $arUser["NAME"];
                $result[$k]["LAST_NAME"] = $arUser["LAST_NAME"];
                $result[$k]["WORK_COMPANY"] = $arUser["WORK_COMPANY"];

                $role = '';
                $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                foreach ($arGroups as $grp) {
                    if ($grp==52) {
                        $arrrole[] = 'Buyer';
                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "	SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391"));

                        if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                            $result[$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                        else
                            $result[$k]["PERSONAL_PHOTO"] = '';

                        $result[$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                        $result[$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                        $result[$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                        $result[$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"];
                    }
                    if ($grp==50) {
                        $arrrole[] = 'Exhibitor';
                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_772","SIMPLE_QUESTION_652"));
                        //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                        //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];

                        if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                            $result[$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                        else
                            $result[$k]["PERSONAL_PHOTO"] = '';
                        //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                        $result[$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];

                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                        $result[$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                        $result[$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                        $result[$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"];
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

                $sql = mysqli_query("SELECT * FROM `chat_messages` WHERE (`AUTHOR_ID` = '" . $arUser["ID"] . "' AND `TO_USER` = '" . $USER->GetID() . "') OR (`AUTHOR_ID` = '" . $USER->GetID() . "' AND `TO_USER` = '" . $arUser["ID"] . "') ", $this->db);
                $result[$k]["MESSAGES_COUNT"] = mysqli_num_rows($sql);


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
                    $arrIds[$arUsers["ID"]] = $arUsers["ID"];
                }
                $arFilter = array("LAST_NAME"=>$this->_request["q"]."%");
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                while($arUsers = $rsUsers->getNext()) {
                    $arrIds[$arUsers["ID"]] = $arUsers["ID"];
                }
                $arFilter = array("LOGIN"=>$this->_request["q"]."%");
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                while($arUsers = $rsUsers->getNext()) {
                    $arrIds[$arUsers["ID"]] = $arUsers["ID"];
                }
                $arFilter = array("EMAIL"=>$this->_request["q"]."%");
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                while($arUsers = $rsUsers->getNext()) {
                    $arrIds[$arUsers["ID"]] = $arUsers["ID"];
                }
                $arFilter = array("KEYWORDS"=>$this->_request["q"]."%");
                $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
                while($arUsers = $rsUsers->getNext()) {
                    $arrIds[$arUsers["ID"]] = $arUsers["ID"];
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
                $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $arFilter, array("NAV_PARAMS" => array("nPageSize" => $limit, "iNumPage" => $offset), "SELECT"=>array("UF_*")));
                //$rsUsers->NavStart($limit);
                while ($arUsers = $rsUsers->getNext()) {
                    $is_fav = '';
                    $result[$k]["ID"] = $arUsers["ID"];
                    $result[$k]["NAME"] = $arUsers["NAME"];
                    $result[$k]["LAST_NAME"] = $arUsers["LAST_NAME"];
                    $result[$k]["WORK_COMPANY"] = $arUsers["WORK_COMPANY"];

                    $role = '';
                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if ($grp==52) {
                            $arrrole[] = 'Buyer';
                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "	SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391"));

                            if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                $result[$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                            else
                                $result[$k]["PERSONAL_PHOTO"] = '';

                            $result[$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                            $result[$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                            $result[$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                            $result[$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"];
                        }
                        if ($grp==50) {
                            $arrrole[] = 'Exhibitor';
                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_772","SIMPLE_QUESTION_652"));
                            //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                            //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];

                            if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                $result[$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                            else
                                $result[$k]["PERSONAL_PHOTO"] = '';
                            //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                            $result[$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];

                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                            $result[$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                            $result[$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                            $result[$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"];
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
            //0.00045 - koef = 500 meters
            $rsUserMe = $USER->GetByID($USER->GetID());
            $arUserMe = $rsUserMe->Fetch();
            $FindLatMin = $arUserMe["UF_LAT"] - 0.00045;
            $FindLatMax = $arUserMe["UF_LAT"] + 0.00045;
            $FindLngMin = $arUserMe["UF_LNG"] - 0.00045;
            $FindLngMax = $arUserMe["UF_LNG"] + 0.00045;

            $k=0;

            $arFilter = array("GROUPS_ID"=>array(54));
            $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
            $rsUsers->NavStart($limit);
            while($arUser = $rsUsers->getNext()) {
                $result["ADMIN"]["ID"] = $arUser["ID"];
                $result["ADMIN"]["NAME"] = $arUser["NAME"];
                $result["ADMIN"]["LAST_NAME"] = $arUser["LAST_NAME"];
                $result["ADMIN"]["WORK_COMPANY"] = $arUser["WORK_COMPANY"];

                $result["ADMIN"]["IS_ONLINE"] = 1;
                $result["ADMIN"]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arUser["PERSONAL_PHOTO"])."&w=242&h=242&zc=4";
                $result["ADMIN"]["WORK_COUNTRY"] = "Russia";
                $result["ADMIN"]["WORK_CITY"] = "Moscow";
                $result["ADMIN"]["ABOUT"] = "";
                $result["ADMIN"]["WORK_POSITION"] = "Administrator";
                $result["ADMIN"]["ROLE"] = iconv("CP1251","UTF-8","Administrator");
            }



            $arFilter = array(">=UF_LAT" => $FindLatMin, "<=UF_LAT" => $FindLatMax, ">=UF_LNG" => $FindLngMin, "<=UF_LNG" => $FindLngMax, "!ID"=>$USER->GetID());
            $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $arFilter, array("NAV_PARAMS" => array("nPageSize" => $limit, "iNumPage" => $offset), "SELECT"=>array("UF_*")));
            //$rsUsers->NavStart($limit);
            while ($arUsers = $rsUsers->getNext()) {

                $is_fav = '';
                $rsUser = $USER->GetByID($arUsers["ID"]);
                $arUser = $rsUser->Fetch();

                $result["USERS"][$k]["ID"] = $arUser["ID"];
                $result["USERS"][$k]["NAME"] = $arUser["NAME"];
                $result["USERS"][$k]["LAST_NAME"] = $arUser["LAST_NAME"];
                $result["USERS"][$k]["WORK_COMPANY"] = $arUser["WORK_COMPANY"];


                $role = '';
                $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                foreach ($arGroups as $grp) {
                    if ($grp==52) {
                        $arrrole[] = 'Buyer';
                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "	SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391"));

                        if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                            $result["USERS"][$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                        else
                            $result["USERS"][$k]["PERSONAL_PHOTO"] = '';

                        $result["USERS"][$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                        $result["USERS"][$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                        $result["USERS"][$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                        $result["USERS"][$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"];
                    }
                    if ($grp==50) {
                        $arrrole[] = 'Exhibitor';
                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_772","SIMPLE_QUESTION_652"));
                        //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                        //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];

                        if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                            $result["USERS"][$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                        else
                            $result["USERS"][$k]["PERSONAL_PHOTO"] = '';
                        //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                        $result["USERS"][$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];


                        $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                        $result["USERS"][$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                        $result["USERS"][$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                        $result["USERS"][$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"];
                    }
                }
                if (!empty($arrrole)) $role = implode(", ", $arrrole);
                $result["USERS"][$k]["ROLE"] = iconv("CP1251","UTF-8",$role);
                if ($arUser["UF_IS_ONLINE"]) $result["USERS"][$k]["IS_ONLINE"] = 1; else $result["USERS"][$k]["IS_ONLINE"] = 0;
                $arFilter2 = array("ID"=>$row["SENDER_ID"], "UF_FAVORITES"=>$arUsers["ID"]);
                $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                while($arUsers2 = $rsUsers2->getNext()) {
                    $is_fav = 1;
                }
                if ($is_fav) $result["USERS"][$k]["IS_FAVORITE"] = 1; else $result["USERS"][$k]["IS_FAVORITE"] = 0;

                //GROUP BY TO_USER

                $sql = mysqli_query("SELECT * FROM `chat_messages` WHERE (`AUTHOR_ID` = '" . $arUser["ID"] . "' AND `TO_USER` = '" . $USER->GetID() . "') OR (`AUTHOR_ID` = '" . $USER->GetID() . "' AND `TO_USER` = '" . $arUser["ID"] . "') ", $this->db);
                $result["USERS"][$k]["MESSAGES_COUNT"] = mysqli_num_rows($sql);

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

                $sql = mysqli_query("SELECT * FROM `chat_messages` WHERE `AUTHOR_ID` = '" . $this->_request['user_id'] . "' GROUP BY TO_USER", $this->db);
                $messagesCount = mysqli_num_rows($sql);

                $result["blockedCount"] = $blockedCount;
                $result["messagesCount"] = $messagesCount;

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

                $VOTE_ID = 4;

                mysqli_query("INSERT INTO `b_vote_event` (`ID`, `VOTE_ID`, `VOTE_USER_ID`, `DATE_VOTE`, `STAT_SESSION_ID`, `IP`, `VALID`) VALUES (NULL, '".$VOTE_ID."', '".$USER->GetId()."', '".date("Y-m-d H:i:s",time())."', '', '".$_SERVER["REMOTE_ADDR"]."', 'Y'");
                $eventID = mysqli_insert_id();


                if ($_REQUSEST["points"]) {
                    mysqli_query("INSERT INTO `b_vote_event_question` (`ID`, `EVENT_ID`, `QUESTION_ID`) VALUES (NULL, '" . $eventID . "', '13')");
                    $eventQID = mysqli_insert_id();
                    mysqli_query("INSERT INTO `test6`.`b_vote_event_answer` (`ID`, `EVENT_QUESTION_ID`, `ANSWER_ID`, `MESSAGE`) VALUES (NULL, '".$eventQID."', '0', '".$_REQUSEST["points"]."')");
                }
                if ($_REQUSEST["buyer_id"]) {
                    mysqli_query("INSERT INTO `b_vote_event_question` (`ID`, `EVENT_ID`, `QUESTION_ID`) VALUES (NULL, '" . $eventID . "', '14')");
                    $eventQID = mysqli_insert_id();
                    mysqli_query("INSERT INTO `test6`.`b_vote_event_answer` (`ID`, `EVENT_QUESTION_ID`, `ANSWER_ID`, `MESSAGE`) VALUES (NULL, '".$eventQID."', '0', '".$_REQUSEST["points"]."')");
                }

                foreach ($_REQUSEST["answ"] as $k=>$v) {
                    if ($k AND $v) {
                        mysqli_query("INSERT INTO `b_vote_event_question` (`ID`, `EVENT_ID`, `QUESTION_ID`) VALUES (NULL, '" . $eventID . "', '".$k."')");
                        $eventQID = mysqli_insert_id();
                        mysqli_query("INSERT INTO `test6`.`b_vote_event_answer` (`ID`, `EVENT_QUESTION_ID`, `ANSWER_ID`, `MESSAGE`) VALUES (NULL, '".$eventQID."', '".$v."', '')");
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

                $favorites = $arUser["UF_BLOCK_LIST"];
                foreach ($favorites as $fav) {
                    $arrFav[$fav] = $fav;
                }
                $arrFav[$this->_request['user_id']] = $this->_request['user_id'];
                $fields = Array("UF_BLOCK_LIST"=>$arrFav);
                $USER->Update($USER->GetID(), $fields);

                $result = array('status' => "Success", "msg" => "You have blocked ".$arUser["NAME"]);
                $this->response($this->json($result), 200);

            } else {
                $result = array('status' => "Error", "msg" => "User not found");
                $this->response($this->json($result), 200);
            }

        } elseif ($_REQUEST['func']=="removeFromBlockList" AND $this->get_request_method() == "POST") {


            if (intval($this->_request['user_id'])>0) {

                $rsUser = $USER->GetByID($USER->GetID());
                $arUser = $rsUser->Fetch();

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

                $result = array('status' => "Success", "msg" => "User has been added to Favourites");
                $this->response($this->json($result), 200);

            } else {
                $result = array('status' => "Error", "msg" => "User not found");
                $this->response($this->json($result), 200);
            }

        } elseif ($_REQUEST['func']=="removeFromFavorite" AND $this->get_request_method() == "POST") {


            if (intval($this->_request['user_id'])>0) {

                $rsUser = $USER->GetByID($USER->GetID());
                $arUser = $rsUser->Fetch();

                $favorites = $arUser["UF_FAVORITES"];
                foreach ($favorites as $fav) {
                    if ($this->_request['user_id']!=$fav) $arrFav[$fav] = $fav;
                }
                $fields = Array("UF_FAVORITES"=>$arrFav);
                $USER->Update($USER->GetID(), $fields);

                $result = array('status' => "Success", "msg" => "User has been deleted from Favorties");
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
                $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_ring.php?user_id='.$this->_request['user_id'].'&from_user_id='.CUser::GetID().'&'.$tokens, false, $context);
                //echo $file;

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
                $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_abuse.php?user_id='.$this->_request['user_id'].'&from_user_id='.CUser::GetID().'&'.$tokens, false, $context);
                //echo $file;

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

            $USER->Logout();
            $result = array('status' => "Success", "msg" => "Successful exit");
            $this->response($this->json($result), 200);

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

        if ($this->_request['func']=="getUserShedule" AND $this->get_request_method() == "GET") {

            if (intval($this->_request["SENDER_ID"])>0) {

                $k=0;
                $sql = mysqli_query("SELECT * FROM `ltm_schedule` WHERE `SENDER_ID` = '".$this->_request["SENDER_ID"]."'", $this->db);
                if(mysqli_num_rows($sql) > 0) {
                    while ($row = mysqli_fetch_array($sql, mysqli_ASSOC)) {
                        $result[$k]["ID"] = iconv("CP1251","UTF-8",$row["ID"]);

                        $rsUser = $USER->GetByID($row["SENDER_ID"]);
                        $arUser = $rsUser->Fetch();

                        $result[$k]["SENDER"]["ID"] = $arUser["ID"];
                        $result[$k]["SENDER"]["NAME"] = $arUser["NAME"];
                        $result[$k]["SENDER"]["LAST_NAME"] = $arUser["LAST_NAME"];
                        $result[$k]["SENDER"]["WORK_COMPANY"] = $arUser["WORK_COMPANY"];


                        $role = '';
                        $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                        foreach ($arGroups as $grp) {
                            if ($grp==52) {
                                $arrrole[] = 'Buyer';
                                $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "	SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391"));

                                if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                    $result[$k]["SENDER"]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                                else
                                    $result[$k]["SENDER"]["PERSONAL_PHOTO"] = '';

                                $result[$k]["SENDER"]["SENDER"]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                                $result[$k]["SENDER"]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                                $result[$k]["SENDER"]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                                $result[$k]["SENDER"]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"];
                            }
                            if ($grp==50) {
                                $arrrole[] = 'Exhibitor';
                                $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_772","SIMPLE_QUESTION_652"));
                                //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                                //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];

                                if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                    $result[$k]["SENDER"]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                                else
                                    $result[$k]["SENDER"]["PERSONAL_PHOTO"] = '';
                                //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                                $result[$k]["SENDER"]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];

                                $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                                $result[$k]["SENDER"]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                                $result[$k]["SENDER"]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                                $result[$k]["SENDER"]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"];
                            }
                        }
                        if (!empty($arrrole)) $role = implode(", ", $arrrole);
                        $result[$k]["SENDER"]["ROLE"] = iconv("CP1251","UTF-8",$role);

                        if ($arUser["UF_IS_ONLINE"]) $result[$k]["SENDER"]["IS_ONLINE"] = 1; else $result[$k]["SENDER"]["IS_ONLINE"] = 0;

                        $arFilter2 = array("ID"=>$row["SENDER_ID"], "UF_FAVORITES"=>$arUsers["ID"]);
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
                        $result[$k]["RECEIVER"]["WORK_COMPANY"] = $arUser["WORK_COMPANY"];


                        $role = '';
                        $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                        foreach ($arGroups as $grp) {
                            if ($grp==52) {
                                $arrrole[] = 'Buyer';
                                $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "	SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391"));

                                if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                    $result[$k]["RECEIVER"]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                                else
                                    $result[$k]["RECEIVER"]["PERSONAL_PHOTO"] = '';

                                $result[$k]["RECEIVER"]["SENDER"]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                                $result[$k]["RECEIVER"]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                                $result[$k]["RECEIVER"]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                                $result[$k]["RECEIVER"]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"];
                            }
                            if ($grp==50) {
                                $arrrole[] = 'Exhibitor';
                                $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_772","SIMPLE_QUESTION_652"));
                                //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                                //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];

                                if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                    $result[$k]["RECEIVER"]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                                else
                                    $result[$k]["RECEIVER"]["PERSONAL_PHOTO"] = '';
                                //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                                $result[$k]["RECEIVER"]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];

                                $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                                $result[$k]["RECEIVER"]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                                $result[$k]["RECEIVER"]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                                $result[$k]["RECEIVER"]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"];
                            }
                        }
                        if (!empty($arrrole)) $role = implode(", ", $arrrole);
                        $result[$k]["RECEIVER"]["ROLE"] = iconv("CP1251","UTF-8",$role);

                        if ($arUser["UF_IS_ONLINE"]) $result[$k]["RECEIVER"]["IS_ONLINE"] = 1; else $result[$k]["RECEIVER"]["IS_ONLINE"] = 0;

                        $arFilter2 = array("ID"=>$row["SENDER_ID"], "UF_FAVORITES"=>$arUsers["ID"]);
                        $rsUsers2 = CUser::GetList(($by="id"), ($order="desc"), $arFilter2);
                        while($arUsers2 = $rsUsers2->getNext()) {
                            $is_fav = 1;
                        }
                        if ($is_fav) $result[$k]["RECEIVER"]["IS_FAVORITE"] = 1; else $result[$k]["RECEIVER"]["IS_FAVORITE"] = 0;

                        /*$result["CREATED_AT"] = iconv("CP1251","UTF-8",$row["CREATED_AT"]);
                        $result["UPDATED_AT"] = iconv("CP1251","UTF-8",$row["UPDATED_AT"]);
                        $result["MODIFIED_BY"] = iconv("CP1251","UTF-8",$row["MODIFIED_BY"]);*/
                        $result[$k]["STATUS"] = $row["STATUS"];
                        $result[$k]["EXHIBITION_ID"] = $row["EXHIBITION_ID"];
                        $result[$k]["TIME-FROM"] = $row["TIME-FROM"];
                        $result[$k]["TIME-TO"] = $row["TIME-TO"];

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

        } elseif ($this->_request['func']=="postUserShedule" AND $this->get_request_method() == "POST") {

            if (intval($this->_request["SENDER_ID"])>0 AND $this->_request["RECEIVER_ID"] AND intval($this->_request["EXHIBITION_ID"])>0 AND intval($this->_request["TIME-FROM"])>0 AND intval($this->_request["TIME-TO"])>0) {
                $result = mysqli_query("INSERT INTO `ltm_schedule` (`ID`, `SENDER_ID`, `RECEIVER_ID`, `CREATED_AT`, `UPDATED_AT`, `MODIFIED_BY`, `STATUS`, `EXHIBITION_ID`, `TIME-FROM`, `TIME-TO`) VALUES (NULL, '".$this->_request["SENDER_ID"]."', '".$this->_request["RECEIVER_ID"]."', '".date("Y-m-d H:i:s",time())."', '".date("Y-m-d H:i:s",time())."', '".$this->_request["SENDER_ID"]."', 'process', '".$this->_request["EXHIBITION_ID"]."', '".$this->_request["TIME-FROM"]."', '".$this->_request["TIME-TO"]."')", $this->db);
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
                $result = mysqli_query("UPDATE `ltm_schedule` SET `STATUS`='".$this->_request["STATUS"]."' WHERE `ID`='".$this->_request["ID"]."' AND `SENDER_ID`='".$this->_request["SENDER_ID"]."' ", $this->db);
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
                $result = mysqli_query("UPDATE `ltm_schedule` SET `STATUS`='".$this->_request["STATUS"]."' WHERE `ID`='".$this->_request["ID"]."' AND `RECEIVER_ID`='".$this->_request["RECEIVER_ID"]."' ", $this->db);
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

                $sql = mysqli_query("SELECT * FROM `chat_messages` WHERE `ID` = '" . $this->_request["ID"] . "' AND `AUTHOR_ID` = '" . $this->_request["AUTHOR_ID"] . "' AND TO_USER = '" . $this->_request["TO_USER"] . "'", $this->db);
                if (mysqli_num_rows($sql) > 0) {
                    while ($row = mysqli_fetch_array($sql, mysqli_ASSOC)) {
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

                $sql = mysqli_query("SELECT * FROM `chat_admin_messages` WHERE `ID` = '".$this->_request["ID"]."' AND `AUTHOR_ID` = '".$this->_request["AUTHOR_ID"]."' AND TO_USER = '".$this->_request["TO_USER"]."'", $this->db);
                if(mysqli_num_rows($sql) > 0) {
                    while ($row = mysqli_fetch_array($sql, mysqli_ASSOC)) {
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

                $sql = mysqli_query("SELECT * FROM `chat_messages` WHERE (AUTHOR_ID = '".$this->_request["AUTHOR_ID"]."' AND TO_USER = '".$this->_request["TO_USER"]."') OR ( TO_USER = '".$this->_request["AUTHOR_ID"]."' AND AUTHOR_ID = '".$this->_request["TO_USER"]."') ORDER BY DATE_CREATE DESC LIMIT ".$offset.",".$limit, $this->db);
                if(mysqli_num_rows($sql) > 0) {
                    $k=0;
                    while ($row = mysqli_fetch_array($sql, mysqli_ASSOC)) {
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

                $sql = mysqli_query("SELECT * FROM `chat_admin_messages` WHERE (AUTHOR_ID = '".$admin_id."' AND TO_USER = '".$this->_request["TO_USER"]."') OR ( TO_USER = '".$admin_id."' AND AUTHOR_ID = '".$this->_request["TO_USER"]."') ORDER BY DATE_CREATE DESC LIMIT ".$offset.",".$limit, $this->db);
                if(mysqli_num_rows($sql) > 0) {
                    $k=0;
                    while ($row = mysqli_fetch_array($sql, mysqli_ASSOC)) {
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

            $sql = mysqli_query("SELECT * FROM `chat_messages` WHERE AUTHOR_ID = '" . $USER->GetId() . "' GROUP BY TO_USER ORDER BY DATE_CREATE DESC ", $this->db);
            if (mysqli_num_rows($sql) > 0)  {
                $k=0;
                while ($row = mysqli_fetch_array($sql, mysqli_ASSOC)) {

                    $is_fav = '';
                    $rsUser = $USER->GetByID($row["TO_USER"]);
                    $arUser = $rsUser->Fetch();

                    $result[$k]["ID"] = $arUser["ID"];
                    $result[$k]["NAME"] = $arUser["NAME"];
                    $result[$k]["LAST_NAME"] = $arUser["LAST_NAME"];
                    $result[$k]["WORK_COMPANY"] = $arUser["WORK_COMPANY"];


                    $role = '';
                    $arGroups = CUser::GetUserGroup($arUsers["ID"]);
                    foreach ($arGroups as $grp) {
                        if ($grp==52) {
                            $arrrole[] = 'Buyer';
                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_672", "SIMPLE_QUESTION_678", "SIMPLE_QUESTION_166", "	SIMPLE_QUESTION_269", "SIMPLE_QUESTION_391"));

                            if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                $result[$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_269"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                            else
                                $result[$k]["PERSONAL_PHOTO"] = '';

                            $result[$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                            $result[$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];
                            $result[$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                            $result[$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_391"][0]["USER_TEXT"];
                        }
                        if ($grp==50) {
                            $arrrole[] = 'Exhibitor';
                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_MSCAUT2015"], array("SIMPLE_QUESTION_772","SIMPLE_QUESTION_652"));
                            //$result["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_678"][0]["ANSWER_TEXT"];
                            //$result["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_672"][0]["ANSWER_TEXT"];

                            if ($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"]>0)
                                $result[$k]["PERSONAL_PHOTO"] = "http://".SITE_SERVER_NAME."/image.php?src=".CFile::GetPath($arAnswer["SIMPLE_QUESTION_772"][0]["USER_FILE_ID"])."&w=242&h=242&zc=4";
                            else
                                $result[$k]["PERSONAL_PHOTO"] = '';
                            //$result["ABOUT"] = $arAnswer["SIMPLE_QUESTION_166"][0]["USER_TEXT"];
                            $result[$k]["WORK_POSITION"] = $arAnswer["SIMPLE_QUESTION_652"][0]["USER_TEXT"];

                            $arAnswer = CFormResult::GetDataByID($arUsers["UF_ID_COMP"], array("SIMPLE_QUESTION_320","SIMPLE_QUESTION_778","SIMPLE_QUESTION_163"));
                            $result[$k]["WORK_COUNTRY"] = $arAnswer["SIMPLE_QUESTION_320"][0]["USER_TEXT"];
                            $result[$k]["WORK_CITY"] = $arAnswer["SIMPLE_QUESTION_778"][0]["USER_TEXT"];
                            $result[$k]["ABOUT"] = $arAnswer["SIMPLE_QUESTION_163"][0]["USER_TEXT"];
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

                    $last_date = $last_mess = '';
                    $sql2 = mysqli_query("SELECT * FROM `chat_messages` WHERE (AUTHOR_ID = '" . $USER->GetId() . "' AND TO_USER = '" . $row["TO_USER"] . "') OR (AUTHOR_ID = '" . $row["TO_USER"] . "' AND TO_USER = '" . $USER->GetId() . "') ORDER BY DATE_CREATE DESC LIMIT 0,1 ", $this->db);
                    while ($row2 = mysqli_fetch_array($sql2, mysqli_ASSOC)) {
                        $last_date = $row2["DATE_CREATE"];
                        $last_mess = $row2["MESSAGE"];
                    }

                    $result[$k]["LAST_MESSAGE"]["DATE_CREATE"] = $last_date;
                    $result[$k]["LAST_MESSAGE"]["MESSAGE"] = $last_mess;


                    $k++;
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
                $result = mysqli_query("INSERT INTO  `chat_messages` (`ID`,`AUTHOR_ID`,`TO_USER`,`MESSAGE`,`DATE_CREATE`) VALUES (NULL,'".$this->_request["AUTHOR_ID"]."','".$this->_request["TO_USER"]."','".strip_tags($this->_request["MESSAGE"])."','".$data."')", $this->db);
                $ID = mysqli_insert_id($this->db);


                if (intval($result)>0) {
                    $arResult = array("ID"=>$ID, "AUTHOR_ID"=>$this->_request["AUTHOR_ID"], "MESSAGE"=>$this->_request["MESSAGE"], "DATE_CREATE"=>$data);


                    $rsUser = $USER->GetByID($this->_request["TO_USER"]);
                    $arUser = $rsUser->Fetch();

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
                    $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess.php?user_id='.$this->_request["AUTHOR_ID"].'&alert='.urlencode(substr($this->_request["MESSAGE"],0,70)).'&from_user_id='.$this->_request["AUTHOR_ID"].'&'.$tokens, false, $context);
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
                $result = mysqli_query("INSERT INTO  `chat_admin_messages` (`ID`,`AUTHOR_ID`,`TO_USER`,`MESSAGE`,`DATE_CREATE`) VALUES (NULL,'".$this->_request["AUTHOR_ID"]."','".$this->_request["TO_USER"]."','".strip_tags($this->_request["MESSAGE"])."','".$data."')", $this->db);
                $ID = mysqli_insert_id($this->db);


                if (intval($result)>0) {
                    $arResult = array("ID"=>$ID, "AUTHOR_ID"=>$this->_request["AUTHOR_ID"], "MESSAGE"=>$this->_request["MESSAGE"], "DATE_CREATE"=>$data);


                    $rsUser = $USER->GetByID($this->_request["TO_USER"]);
                    $arUser = $rsUser->Fetch();

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
                    $file = file_get_contents('http://app.luxurytravelmart.ru/rst/push/push_mess.php?user_id='.$this->_request["AUTHOR_ID"].'&alert='.urlencode(substr($this->_request["MESSAGE"],0,70)).'&from_user_id='.$this->_request["AUTHOR_ID"].'&'.$tokens, false, $context);
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
            mysqli_query("DELETE FROM users WHERE user_id = $id");
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
