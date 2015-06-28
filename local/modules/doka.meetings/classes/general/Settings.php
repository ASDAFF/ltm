<?php
namespace Doka\Meetings;

require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/doka.meetings/classes/mysql/Entity/Setting.php");


use Doka\Meetings\Entity\Setting as DokaSetting;
IncludeModuleLangFile(__FILE__);

/**
 * Class Meetings
 */
class Settings extends DokaSetting
{
    public function __construct()
    {
        // \CModule::IncludeModule("iblock");
        $this->options = array(
            // 'iblock_comments_id' => COption::GetOptionInt(self::$module, 'cto_comments_iblock_id'),
        );
    }

    public function getOption($name)
    {
        if (isset($this->options[$name]))
            return $this->options[$name];

        return false;
    }



    function GetByID($ID)
    {
        return self::GetList(Array(), Array("id" => $ID));
    }
}