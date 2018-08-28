<?php
/**
 * Class Migration1531900158
 *
 * @author       : vz
 * @documentation: http://cjp2600.github.io/bim-core/
 */

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Spectr\Meeting\Models\CompanyScheduleTable;

class Migration1531900158 implements Bim\Revision
{

    private static $author = "vz";
    private static $description = "";

    /**
     * up
     *
     * @success : return void or true;
     * @error   : return false, or Exception
     */
    public static function up()
    {
        if (Loader::includeModule("doka.meetings")) {
            CompanyScheduleTable::getEntity()->createDbTable();
        }
    }

    /**
     * down
     *
     * @success : return void or true;
     * @error   : return false, or Exception
     */
    public static function down()
    {
        if (Loader::includeModule("doka.meetings")) {
            Application::getConnection()->dropTable(CompanyScheduleTable::getTableName());
        }
        // do down code
        // empty down method (use Exception)

    }

    /**
     * getDescription
     *
     * @return string
     */
    public static function getDescription()
    {
        return self::$description;
    }

    /**
     * getAuthor
     *
     * @return string
     */
    public static function getAuthor()
    {
        return self::$author;
    }

}