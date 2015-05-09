<?
IncludeModuleLangFile(__FILE__);
Class doka_meetings extends CModule
{
	const MODULE_ID = 'doka.meetings';
	var $MODULE_ID = 'doka.meetings'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("doka.meetings_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("doka.meetings_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("doka.meetings_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("doka.meetings_PARTNER_URI");

		CModule::IncludeModule("iblock");

		$this->options = array(
			'LID' => 'ru', // СЏР·С‹Рє РёРЅС‚РµСЂС„РµР№СЃР°
		);
		// РёРґРµРЅС‚РёС„РёРєР°С‚РѕСЂС‹ СЃР°Р№С‚РѕРІ
		$rsSites = CSite::GetList($by="sort", $order="desc", array("ACTIVE" => "Y"));
		while ($arSite = $rsSites->Fetch()) {
			$this->options['SID'][] = $arSite['ID'];
		}
	}

	function InstallDB($arParams = array())
	{
        global $APPLICATION, $DB, $errors;
        $db_errors = $DB->RunSQLBatch(dirname(__FILE__).'/db/'.strtolower($DB->type).'/install.sql');
        if (!empty($db_errors)) {
            foreach($db_errors as $error) {
                $errors[] = $error;                
            }
            $APPLICATION->ThrowException(implode('', $db_errors));
            return false;
        }
		return true;
	}

	function UnInstallDB($arParams = array())
	{
        global $APPLICATION, $DB, $errors;
        
        $errors = $DB->RunSQLBatch(dirname(__FILE__).'/db/'.strtolower($DB->type).'/uninstall.sql');
        if (!empty($errors)) {
            $APPLICATION->ThrowException(implode('', $errors));
            return false;
        }
		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles($arParams = array())
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.' || $item == 'menu.php')
						continue;
					file_put_contents($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item,
					'<'.'? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/'.self::MODULE_ID.'/admin/'.$item.'");?'.'>');
				}
				closedir($dir);
			}
		}
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;
					CopyDirFiles($p.'/'.$item, $_SERVER['DOCUMENT_ROOT'].'/bitrix/components/'.$item, $ReWrite = True, $Recursive = True);
				}
				closedir($dir);
			}
		}
		return true;
	}

	function UnInstallFiles()
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;
					unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item);
				}
				closedir($dir);
			}
		}
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.' || !is_dir($p0 = $p.'/'.$item))
						continue;

					$dir0 = opendir($p0);
					while (false !== $item0 = readdir($dir0))
					{
						if ($item0 == '..' || $item0 == '.')
							continue;
						DeleteDirFilesEx('/bitrix/components/'.$item.'/'.$item0);
					}
					closedir($dir0);
				}
				closedir($dir);
			}
		}
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
		$this->InstallEvents();
		RegisterModule(self::MODULE_ID);
	}

	function DoUninstall()
	{
		global $APPLICATION;
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();
	}
}
?>
