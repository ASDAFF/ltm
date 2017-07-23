<?php

namespace Ltm\Domain\Service;

use Ltm\Domain\Util\ArrayAccessTrait;
use Bitrix\Main\Application;


class MySql implements \ArrayAccess
{
    use ArrayAccessTrait;

    protected function get($connectionName)
    {
        $connection = Application::getConnection($connectionName);

        $connection->queryExecute("SET NAMES 'utf8'");
        $connection->queryExecute('SET collation_connection = "utf8_unicode_ci"');

        return $connection;
    }
}
