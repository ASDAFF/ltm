<?php
namespace Bim\Db\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

use Bim\Util\Config;

/**
 * Class MigrationsTable
 *
 * Documentation: http://cjp2600.github.io/bim-core/
 * @package Bim\Db\Entity
 */
class MigrationsTable
{
    /**
     * getTableName
     * @return string
     */
    public static function getTableName()
    {
        $conf = new Config(__DIR__ . "/../../config/bim.json");
        return $conf->get("migration_table");
    }

    /**
     * isExistsInTable
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function isExistsInTable($id)
    {
        # check migration tables
        self::checkMigrationTable();

        global $DB;
        if ($result = $DB->Query("SELECT 'id' FROM " . self::getTableName() . " WHERE id = '" . $id . "'", true)) {
            if ($result->AffectedRowsCount()) {
                return true;
            }
        } else {
            throw new \Exception($DB->GetErrorMessage());
        }
        return false;
    }

    /**
     * add
     * @param $id
     * @return bool
     * @throws Exception
     */
    public static function add($id)
    {
        global $DB;
        if (!self::isExistsInTable($id)) {
            $DB->Add(self::getTableName(), array(
                "id" => $id
            ));
            if (self::isExistsInTable($id)) {
                return true;
            }
        }
        return false;
    }

    /**
     * delete
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id)
    {
        global $DB;
        if ($result = $DB->Query("DELETE FROM " . self::getTableName() . " WHERE id = '" . $id . "'", true)) {
            return true;
        } else {
            throw new \Exception($DB->GetErrorMessage());
        }
        return false;
    }


    /**
     * checkMigrationTable
     * @throws Exception
     */
    public static function checkMigrationTable()
    {
        global $DB;
        if (!$DB->Query("SELECT 'id' FROM " . self::getTableName(), true)) {
            throw new \Exception("Migration table not found, run init command. Example: php bim init");
        }
    }

    /**
     * createTable
     * @return bool
     * @throws \Exception
     */
    public static function createTable()
    {
        global $DB;
        $errors = false;
        if (!$DB->Query("SELECT 'id' FROM " . self::getTableName(), true)) {
            $errors = $DB->RunSQLBatch(__DIR__ . '/../install/install.sql');
        } else {
            return false;
        }
        if ($errors !== false) {
            throw new \Exception(implode("", $errors));
            return false;
        }
        return true;
    }

}