<?
class Chandlers
{
	function OnBeforeEventAddHandler($event, $lid, $arFields)
	{
	    if ($event == "TEST_NEW_SEND")
	    {
	        require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/lib/mail_attach.php");
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

	/**
	 * Уменьшение размеров фото профиля
	 * @param $WEB_FORM_ID
	 * @param $RESULT_ID
	 */
	function OnProfilePhotoResize($WEB_FORM_ID, $RESULT_ID)
	{
		global $APPLICATION;

		//Участники
		$arParticipantForms = array_values(CFormMatrix::$arExhForm);

		//Участники
		if (in_array($WEB_FORM_ID,$arParticipantForms))
		{
			$ANSWER_ID = CFormMatrix::getAnswerRelBase(195, $WEB_FORM_ID);
			$FIELD_SID = CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_575", $WEB_FORM_ID);
			$arPhoto = CFormResult::GetFileByAnswerID($RESULT_ID, $ANSWER_ID);

			if($arPhoto["USER_FILE_ID"])
			{
				//получаем данные картинки
				$arFile = CFile::MakeFileArray($arPhoto["USER_FILE_ID"]);

				//Получаем размер файла, чтобы определить какую сторону ужимать
				$arFileArray = CFile::GetFileArray($arPhoto["USER_FILE_ID"]);

				if($arFileArray["WIDTH"] > 500 || $arFileArray["HEIGHT"] > 500)
				{
					$arSizes = 	array("width" => 999999, "height" => 999999);

					if($arFileArray["WIDTH"] > 500)
					{
						$arSizes["width"] = 500;
					}

					if($arFileArray["HEIGHT"] > 500)
					{
						$arSizes["HEIGHT"] = 500;
					}

					CFile::ResizeImage($arFile,	$arSizes,	BX_RESIZE_IMAGE_PROPORTIONAL);

					$arVALUE = array();
					$arVALUE[$ANSWER_ID] = $arFile;
					CFormResult::SetField($RESULT_ID, $FIELD_SID, $arVALUE);
				}
			}
		}

		//Гости
		if(10 == $WEB_FORM_ID)
		{
			$arFields = array(
				//фото профиля
				array(
					"ANSWER_ID" => "1312",
					"FIELD_SID" => "SIMPLE_QUESTION_269",
				)
				,//Коллега на утреннюю сессию
				array(
					"ANSWER_ID" => "1313",
					"FIELD_SID" => "SIMPLE_QUESTION_873",
				)
			);

			foreach($arFields as $arField)
			{
				$arPhoto = CFormResult::GetFileByAnswerID($RESULT_ID, $arField["ANSWER_ID"]);

				if($arPhoto["USER_FILE_ID"])
				{
					//получаем данные картинки
					$arFile = CFile::MakeFileArray($arPhoto["USER_FILE_ID"]);

					//Получаем размер файла, чтобы определить какую сторону ужимать
					$arFileArray = CFile::GetFileArray($arPhoto["USER_FILE_ID"]);

					if($arFileArray["WIDTH"] > 500 || $arFileArray["HEIGHT"] > 500)
					{
						$arSizes = 	array("width" => 999999, "height" => 999999);

						if($arFileArray["WIDTH"] > 500)
						{
							$arSizes["width"] = 500;
						}

						if($arFileArray["HEIGHT"] > 500)
						{
							$arSizes["HEIGHT"] = 500;
						}

						CFile::ResizeImage($arFile,	$arSizes,	BX_RESIZE_IMAGE_PROPORTIONAL);

						$arVALUE = array();
						$arVALUE[$arField["ANSWER_ID"]] = $arFile;
						CFormResult::SetField($RESULT_ID, $arField["FIELD_SID"], $arVALUE);
					}
				}
			}
		}
	}

	function OnBuildGlobalMenuHandler(&$adminMenu, &$moduleMenu) {
			global $USER;
			if($USER->IsAdmin()) {
				$adminMenu['global_admin_cab'] = array(
					"menu_id" => "admin_cab",
					"sort"        => 1000,
					"text"        => 'Кабинет админов',       // текст пункта меню
					"title"       => 'Меню кабинета администраторов', // текст всплывающей подсказки
					"icon"        => "form_menu_icon", // малая иконка
					"index_icon"   => "form_page_icon", // большая иконка
					"items_id"    => "global_admin_cab",  // идентификатор ветви
					"items"       => array()          // остальные уровни меню сформируем ниже.
				);

				$moduleMenu[] = array(
					"parent_menu" => "global_admin_cab",
					"section" => "Главная страница",
					"sort"        => 1000,
					"url"         => "/admin/",
					"text"        => 'Главная страница',
					"title"       => 'Главная страница',
					"icon"        => "sys_menu_icon",
					"page_icon"   => "form_page_icon",
					"items_id"    => "admin_cab_main",
					"items"       => array()
				);
		}
	}
}

?>