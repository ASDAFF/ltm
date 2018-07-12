<?php
use Bim\Db\Generator\Code;


/**
 * =================================================================================
 * Создание новых миграций [BIM GEN]
 * =================================================================================
 *
 * Documentation: https://github.com/cjp2600/bim-core#gen
 * =================================================================================
 */
class GenCommand extends BaseCommand
{

    const END_LOOP_SYMBOL = "";

    /**
     * @var Code
     */
    private $generateObject = null;

    private $isMulti = false;
    private $multiAddReturn = array();
    private $multiDeleteReturn = array();
    private $multiHeaders = array();
    private $multiCurrentCommand = null;

    /**
     * execute
     * @param array $args
     * @param array $options
     * @return mixed|void
     * @throws Exception
     */
    public function execute(array $args, array $options = array())
    {
        if (isset($args[0])) {
            #if gen multi command generator
            if (strtolower($args[0]) == "multi") {
                $this->multiCommands($args, $options);
            } else {
                # single command generator
                if (strstr($args[0], ':')) {
                    $ex = explode(":", $args[0]);
                    $this->setGenerateObject(Bim\Db\Generator\Code::buildHandler(ucfirst($ex[0])));
                    $methodName = ucfirst($ex[0]) . ucfirst($ex[1]);
                } else {
                    throw new \Bim\Exception\BimException("Improperly formatted command. Example: php bim gen iblock:add");
                }
                $method = "gen" . $methodName;
                if (method_exists($this, $method)) {
                    $this->{$method}($args, $options);
                } else {
                    throw new \Bim\Exception\BimException("Missing command, see help Example: php bim help gen");
                }
            }
        } else {
            $this->createOther($args, $options);
        }
    }

    /**
     *
     *
     * IblockType
     *
     *
     */

    /**
     * genIblockTypeAdd
     * @param array $args
     * @param array $options
     */
    public function genIblockTypeAdd(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $iblockTypeId = (isset($options['typeId'])) ? $options['typeId'] : false;

        if (!$iblockTypeId) {
            $do = true;
            while ($do) {
                $desk = "Put block type id - no default/required";
                $iblockTypeId = $dialog->ask($desk . PHP_EOL . $this->color('[IBLOCK_TYPE_ID]:',
                        \ConsoleKit\Colors::YELLOW), '', false);
                $iblockDbRes = \CIBlockType::GetByID($iblockTypeId);
                if ($iblockDbRes->SelectedRowsCount()) {
                    $do = false;
                } else {
                    $this->error('Iblock with id = "' . $iblockTypeId . '" not exist.');
                }
            }
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "add";
        $this->_save(
            $this->generateObject->generateAddCode($iblockTypeId),
            $this->generateObject->generateDeleteCode($iblockTypeId),
            $desc,
            $autoTag
        );

    }

    /**
     * genIblocktypeDelete
     * @param array $args
     * @param array $options
     */
    public function genIblocktypeDelete(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $iblockTypeId = (isset($options['typeId'])) ? $options['typeId'] : false;

        if (!$iblockTypeId) {
            $do = true;
            while ($do) {
                $desk = "Put block type id - no default/required";
                $iblockTypeId = $dialog->ask($desk . PHP_EOL . $this->color('[IBLOCK_TYPE_ID]:',
                        \ConsoleKit\Colors::YELLOW), '', false);
                $iblockDbRes = \CIBlockType::GetByID($iblockTypeId);
                if ($iblockDbRes->SelectedRowsCount()) {
                    $do = false;
                } else {
                    $this->error('Iblock with id = "' . $iblockTypeId . '" not exist.');
                }
            }
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "delete";
        $this->_save(
            $this->generateObject->generateDeleteCode($iblockTypeId),
            $this->generateObject->generateAddCode($iblockTypeId)
            , $desc,
            $autoTag
        );

    }


    /**
     *
     *
     * Iblock
     *
     *
     */

    /**
     * createIblock
     * @param array $args
     * @param array $options
     * @throws Exception
     */
    public function genIblockAdd(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $code = (isset($options['code'])) ? $options['code'] : false;

        if (!$code) {
            $do = true;
            while ($do) {
                $desk = "Put code information block - no default/required";
                $code = $dialog->ask($desk . PHP_EOL . $this->color('[IBLOCK_CODE]:', \ConsoleKit\Colors::YELLOW), '',
                    false);
                $iblockDbRes = \CIBlock::GetList(array(), array('CODE' => $code, 'CHECK_PERMISSIONS' => 'N'));
                if ($iblockDbRes->SelectedRowsCount()) {
                    $do = false;
                } else {
                    $this->error('Iblock with code = "' . $code . '" not exist.');
                }
            }
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "add";
        $this->_save(
            $this->generateObject->generateAddCode($code),
            $this->generateObject->generateDeleteCode($code)
            , $desc,
            $autoTag
        );

    }

    /**
     * createIblockDelete
     * @param array $args
     * @param array $options
     */
    public function genIblockDelete(array $args, array $options = array())
    {
        $iBlock = new \CIBlock();
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $code = (isset($options['code'])) ? $options['code'] : false;

        if (!$code) {
            $do = true;
            while ($do) {
                $desk = "Put code information block - no default/required";
                $code = $dialog->ask($desk . PHP_EOL . $this->color('[IBLOCK_CODE]:', \ConsoleKit\Colors::YELLOW), '',
                    false);
                $iblockDbRes = $iBlock->GetList(array(), array('CODE' => $code, 'CHECK_PERMISSIONS' => 'N'));
                if ($iblockDbRes->SelectedRowsCount()) {
                    $do = false;
                } else {
                    $this->error('Iblock with code = "' . $code . '" not exist.');
                }
            }
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "delete";
        $this->_save(
            $this->generateObject->generateDeleteCode($code),
            $this->generateObject->generateAddCode($code)
            , $desc,
            $autoTag
        );

    }

    /**
     *
     *
     * IblockProperty
     *
     *
     */

    /**
     * genIblockPropertyAdd
     * @param array $args
     * @param array $options
     * @throws Exception
     */
    public function genIblockPropertyAdd(array $args, array $options = array())
    {
        $iBlock = new \CIBlock();
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $code = (isset($options['code'])) ? $options['code'] : false;

        if (!$code) {
            $do = true;
            while ($do) {
                $desk = "Put code information block - no default/required";
                $code = $dialog->ask($desk . PHP_EOL . $this->color('[IBLOCK_CODE]:', \ConsoleKit\Colors::YELLOW), '',
                    false);
                $iblockDbRes = $iBlock->GetList(array(), array('CODE' => $code, 'CHECK_PERMISSIONS' => 'N'));
                if ($iblockDbRes->SelectedRowsCount()) {
                    $do = false;
                } else {
                    $this->error('Iblock with code = "' . $code . '" not exist.');
                }
            }
        }

        $propertyCode = (isset($options['propertyCode'])) ? $options['propertyCode'] : false;
        if (!$propertyCode) {
            $do = true;
            while ($do) {
                $desk = "Put property code - no default/required";
                $propertyCode = $dialog->ask($desk . PHP_EOL . $this->color('[PROPERTY_CODE]:',
                        \ConsoleKit\Colors::YELLOW), '', false);
                $IblockProperty = new \CIBlockProperty();
                $dbIblockProperty = $IblockProperty->GetList(array(),
                    array('IBLOCK_CODE' => $code, 'CODE' => $propertyCode));
                if ($arIblockProperty = $dbIblockProperty->Fetch()) {
                    $do = false;
                } else {
                    $this->error('Property with code = "' . $propertyCode . '" not exist.');
                }
            }
        }

        if (!empty($code) && !empty($propertyCode)) {
            $params['iblockCode'] = $code;
            $params['propertyCode'] = $propertyCode;
        } else {
            throw new Exception("Ошибка генерации params");
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "add";
        $this->_save(
            $this->generateObject->generateAddCode($params),
            $this->generateObject->generateDeleteCode($params)
            , $desc,
            $autoTag
        );

    }


    /**
     * genIblockPropertyDelete
     * @param array $args
     * @param array $options
     * @throws Exception
     */
    public function genIblockPropertyDelete(array $args, array $options = array())
    {
        $iBlock = new \CIBlock();
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $code = (isset($options['code'])) ? $options['code'] : false;

        if (!$code) {
            $do = true;
            while ($do) {
                $desk = "Put code information block - no default/required";
                $code = $dialog->ask($desk . PHP_EOL . $this->color('[IBLOCK_CODE]:', \ConsoleKit\Colors::YELLOW), '',
                    false);
                $iblockDbRes = $iBlock->GetList(array(), array('CODE' => $code, 'CHECK_PERMISSIONS' => 'N'));
                if ($iblockDbRes->SelectedRowsCount()) {
                    $do = false;
                } else {
                    $this->error('Iblock with code = "' . $code . '" not exist.');
                }
            }
        }

        $propertyCode = (isset($options['propertyCode'])) ? $options['propertyCode'] : false;
        if (!$propertyCode) {
            $do = true;
            while ($do) {
                $desk = "Put property code - no default/required";
                $propertyCode = $dialog->ask($desk . PHP_EOL . $this->color('[PROPERTY_CODE]:',
                        \ConsoleKit\Colors::YELLOW), '', false);
                $IblockProperty = new \CIBlockProperty();
                $dbIblockProperty = $IblockProperty->GetList(array(),
                    array('IBLOCK_CODE' => $code, 'CODE' => $propertyCode));
                if ($arIblockProperty = $dbIblockProperty->Fetch()) {
                    $do = false;
                } else {
                    $this->error('Property with code = "' . $propertyCode . '" not exist.');
                }
            }
        }

        if (!empty($code) && !empty($propertyCode)) {
            $params['iblockCode'] = $code;
            $params['propertyCode'] = $propertyCode;
        } else {
            throw new Exception("Ошибка генерации params");
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "delete";
        $this->_save(
            $this->generateObject->generateDeleteCode($params),
            $this->generateObject->generateAddCode($params)
            , $desc,
            $autoTag
        );

    }

    /**
     *
     *
     * Highloadblock
     *
     *
     */

    /**
     * genHlblockAdd
     * @param array $args
     * @param array $options
     */
    public function genHlblockAdd(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $hlId = (isset($options['id'])) ? $options['id'] : false;

        if (!$hlId) {
            $do = true;
            while ($do) {
                $desk = "Put id Highloadblock - no default/required";
                $hlId = $dialog->ask($desk . PHP_EOL . $this->color('[HLBLOCK_ID]:', \ConsoleKit\Colors::YELLOW), '',
                    false);
                $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlId)->fetch();
                if ($hlblock) {
                    $do = false;
                } else {
                    $this->error('Highloadblock with id = "' . $hlId . '" not exist.');
                }
            }
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "add";
        $this->_save(
            $this->generateObject->generateAddCode($hlId),
            $this->generateObject->generateDeleteCode($hlId)
            , $desc,
            $autoTag
        );

    }

    /**
     * genHlblockDelete
     * @param array $args
     * @param array $options
     */
    public function genHlblockDelete(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $hlId = (isset($options['id'])) ? $options['id'] : false;

        if (!$hlId) {
            $do = true;
            while ($do) {
                $desk = "Put id Highloadblock - no default/required";
                $hlId = $dialog->ask($desk . PHP_EOL . $this->color('[HLBLOCK_ID]:', \ConsoleKit\Colors::YELLOW), '',
                    false);
                $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlId)->fetch();
                if ($hlblock) {
                    $do = false;
                } else {
                    $this->error('Highloadblock with id = "' . $hlId . '" not exist.');
                }
            }
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "delete";
        $this->_save(
            $this->generateObject->generateDeleteCode($hlId),
            $this->generateObject->generateAddCode($hlId)
            , $desc,
            $autoTag
        );

    }

    /**
     * genHlblockFieldAdd
     * @param array $args
     * @param array $options
     */
    public function genHlblockFieldAdd(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $hlId = (isset($options['hlblockid'])) ? $options['hlblockid'] : false;

        if (!$hlId) {
            $do = true;
            while ($do) {
                $desk = "Put id Highloadblock - no default/required";
                $hlId = $dialog->ask($desk . PHP_EOL . $this->color('[HLBLOCK_ID]:', \ConsoleKit\Colors::YELLOW), '',
                    false);
                $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlId)->fetch();
                if ($hlblock) {
                    $do = false;
                } else {
                    $this->error('Highloadblock with id = "' . $hlId . '" not exist.');
                }
            }
        }

        $hlFieldId = (isset($options['hlFieldId'])) ? $options['hlFieldId'] : false;
        if (!$hlFieldId) {
            $do = true;
            while ($do) {
                $desk = "Put id HighloadblockField (UserField) - no default/required";
                $hlFieldId = $dialog->ask($desk . PHP_EOL . $this->color('[USER_FIELD_ID]:',
                        \ConsoleKit\Colors::YELLOW), '', false);
                $userFieldData = \CUserTypeEntity::GetByID($hlFieldId);
                if ($userFieldData === false || empty($userFieldData)) {
                    $this->error('UserField with id = "' . $hlFieldId . '" not exist.');
                } else {
                    $do = false;
                }
            }
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        # set
        $autoTag = "add";
        $this->_save(
            $this->generateObject->generateAddCode(array("hlblockId" => $hlId, "hlFieldId" => $hlFieldId)),
            $this->generateObject->generateDeleteCode(array("hlblockId" => $hlId, "hlFieldId" => $hlFieldId))
            , $desc,
            $autoTag
        );
    }


    /**
     * genHlblockFieldDelete
     * @param array $args
     * @param array $options
     */
    public function genHlblockFieldDelete(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $hlId = (isset($options['hlblockid'])) ? $options['hlblockid'] : false;

        if (!$hlId) {
            $do = true;
            while ($do) {
                $desk = "Put id Highloadblock - no default/required";
                $hlId = $dialog->ask($desk . PHP_EOL . $this->color('[HLBLOCK_ID]:', \ConsoleKit\Colors::YELLOW), '',
                    false);
                $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlId)->fetch();
                if ($hlblock) {
                    $do = false;
                } else {
                    $this->error('Highloadblock with id = "' . $hlId . '" not exist.');
                }
            }
        }

        $hlFieldId = (isset($options['hlFieldId'])) ? $options['hlFieldId'] : false;
        if (!$hlFieldId) {
            $do = true;
            while ($do) {
                $desk = "Put id HighloadblockField (UserField) - no default/required";
                $hlFieldId = $dialog->ask($desk . PHP_EOL . $this->color('[USER_FIELD_ID]:',
                        \ConsoleKit\Colors::YELLOW), '', false);
                $userFieldData = \CUserTypeEntity::GetByID($hlFieldId);
                if ($userFieldData === false || empty($userFieldData)) {
                    $this->error('UserField with id = "' . $hlFieldId . '" not exist.');
                } else {
                    $do = false;
                }
            }
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        # set
        $autoTag = "delete";
        $this->_save(
            $this->generateObject->generateDeleteCode(array("hlblockId" => $hlId, "hlFieldId" => $hlFieldId)),
            $this->generateObject->generateAddCode(array("hlblockId" => $hlId, "hlFieldId" => $hlFieldId))
            , $desc,
            $autoTag
        );
    }


    /**
     *
     *
     * Group !
     *
     *
     */
    /**
     * genGroupAdd
     * @param array $args
     * @param array $options
     */
    public function genGroupAdd(array $args, array $options = array())
    {
        $group = new \CGroup();
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $groupId = (isset($options['id'])) ? $options['id'] : false;

        if (!$groupId) {
            $do = true;
            while ($do) {
                $desk = "Put id Group - no default/required";

                $groupId = $dialog->ask($desk . PHP_EOL . $this->color('[GROUP_ID]:', \ConsoleKit\Colors::YELLOW), '',
                    false);

                $groupDbRes = $group->GetList($by = 'id', $order = 'desc', array('ID' => $groupId));
                if ($groupDbRes === false || !$groupDbRes->SelectedRowsCount()) {
                    $this->error('Group with id = "' . $groupId . '" not exist.');
                } else {
                    $do = false;
                }

            }
        }
        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "add";
        $this->_save(
            $this->generateObject->generateAddCode($groupId),
            $this->generateObject->generateDeleteCode($groupId)
            , $desc,
            $autoTag
        );
    }

    /**
     * genGroupDelete
     * @param array $args
     * @param array $options
     */
    public function genGroupDelete(array $args, array $options = array())
    {
        $group = new \CGroup();
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $groupId = (isset($options['id'])) ? $options['id'] : false;

        if (!$groupId) {
            $do = true;
            while ($do) {
                $desk = "Put id Group - no default/required";

                $groupId = $dialog->ask($desk . PHP_EOL . $this->color('[GROUP_ID]:', \ConsoleKit\Colors::YELLOW), '',
                    false);

                $groupDbRes = $group->GetList($by = 'id', $order = 'desc', array('ID' => $groupId));
                if ($groupDbRes === false || !$groupDbRes->SelectedRowsCount()) {
                    $this->error('Group with id = "' . $groupId . '" not exist.');
                } else {
                    $do = false;
                }

            }
        }
        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "delete";
        $this->_save(
            $this->generateObject->generateDeleteCode($groupId),
            $this->generateObject->generateAddCode($groupId)
            , $desc,
            $autoTag
        );
    }

    /**
     * genSiteAdd
     * @param array $args
     * @param array $options
     */
    public function genSiteAdd(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $siteId = (isset($options['id'])) ? $options['id'] : false;

        if (!$siteId) {
            $do = true;
            while ($do) {
                $desk = "Put id Site - no default/required";

                $siteId = $dialog->ask($desk . PHP_EOL . $this->color('[SITE_ID]:', \ConsoleKit\Colors::YELLOW), '',
                    false);

                $obSite = new \CSite;
                $dbSite = $obSite->GetList($by = "sort", $order = "desc", array('ID' => $siteId));
                if ($dbSite === false || !$dbSite->SelectedRowsCount()) {
                    $this->error('Site with id = "' . $siteId . '" not exist.');
                } else {
                    $do = false;
                }

            }
        }
        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "add";
        $this->_save(
            $this->generateObject->generateAddCode($siteId),
            $this->generateObject->generateDeleteCode($siteId)
            , $desc,
            $autoTag
        );
    }

    /**
     *
     * Language
     *
     */

    /**
     * genSiteAdd
     * @param array $args
     * @param array $options
     */
    public function genLanguageAdd(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $langId = (isset($options['id'])) ? $options['id'] : false;

        if (!$langId) {
            $do = true;
            while ($do) {
                $desk = "Put id Lang - no default/required";
                $langId = $dialog->ask($desk . PHP_EOL . $this->color('[LANG_ID]:', \ConsoleKit\Colors::YELLOW), '',
                    false);
                $langQuery =  new \CLanguage();
                $lang = $langQuery->GetList($by = "lid", $order = "desc", array('LID' => $langId));
                if ($lang === false || !$lang->SelectedRowsCount()) {
                    $this->error('Language with id = "' . $langId . '" not exist.');
                } else {
                    $do = false;
                }
            }
        }
        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "add";
        $this->_save(
            $this->generateObject->generateAddCode($langId),
            $this->generateObject->generateDeleteCode($langId)
            , $desc,
            $autoTag
        );
    }



    /**
     * genSiteDelete
     * @param array $args
     * @param array $options
     */
    public function genSiteDelete(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $siteId = (isset($options['id'])) ? $options['id'] : false;

        if (!$siteId) {
            $do = true;
            while ($do) {
                $desk = "Put id Site - no default/required";

                $siteId = $dialog->ask($desk . PHP_EOL . $this->color('[SITE_ID]:', \ConsoleKit\Colors::YELLOW), '',
                    false);

                $obSite = new \CSite;
                $dbSite = $obSite->GetList($by = "sort", $order = "desc", array('ID' => $siteId));
                if ($dbSite === false || !$dbSite->SelectedRowsCount()) {
                    $this->error('Site with id = "' . $siteId . '" not exist.');
                } else {
                    $do = false;
                }

            }
        }
        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $autoTag = "delete";
        $this->_save(
            $this->generateObject->generateDeleteCode($siteId),
            $this->generateObject->generateAddCode($siteId)
            , $desc,
            $autoTag
        );
    }


    /**
     *
     *
     * MultiCommands !
     *
     * @param array $args
     * @param array $options
     * @return bool
     * @throws Exception
     */
    public function multiCommands(array $args, array $options = array())
    {
        $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
        $do = true;
        while ($do) {

            $headers = $this->getMultiHeaders();
            if (!empty($headers)) {
                $this->padding(implode(PHP_EOL, $headers));
            }
            $currentCommand = $this->getMultiCurrentCommand();
            if (is_null($currentCommand)) {
                $desk = "Put generation commands:";
                $command = $dialog->ask($desk . " " . $this->color('php bim gen >', \ConsoleKit\Colors::MAGENTA), '',
                    false);
                if (!empty($command)) {
                    if ($command != self::END_LOOP_SYMBOL) {
                        $this->setMulti(true);
                        $this->setMultiCurrentCommand($command);
                        $this->execute(array($command));
                    } else {
                        $do = false;
                    }
                } else {
                    $do = false;
                }
            } else {
                $ask = $dialog->ask("You want to repeat command (" . $this->color($currentCommand,
                        \ConsoleKit\Colors::MAGENTA) . ")", 'Y', true);
                if (strtolower($ask) == "y") {

                    $this->setMulti(true);
                    $this->setMultiCurrentCommand($currentCommand);
                    $this->execute(array($currentCommand));

                } else {
                    $this->setMultiCurrentCommand(null);
                }
            }
        }

        $addItems = $this->getMultiAddReturn();
        if (empty($addItems)) {
            return true;
        }

        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";
        if (empty($desc)) {
            $desk = "Type Description of migration file. Example: #TASK-124";
            $desc = $dialog->ask($desk . PHP_EOL . $this->color('Description:', \ConsoleKit\Colors::BLUE), "", false);
        }

        $up = $this->getMultiAddReturn();
        $down = $this->getMultiDeleteReturn();

        if (count($up) == count($down)) {
            foreach (array("add", "delete") as $it) {

                $i = 0;
                foreach ($up[$it] as $row) {
                    # set
                    $tmpDesc = $desc . " #" . $it;
                    $migrationName = $this->getMigrationName() + ($i * 60);
                    $this->saveTemplate($migrationName,
                        $this->setTemplate(
                            $migrationName,
                            $row,
                            $down[$it][$i],
                            $tmpDesc,
                            get_current_user()
                        ), $it);
                    $i++;
                }
            }
        }
    }


    /**
     *
     *
     * Other
     *
     *
     */

    /**
     * createOther
     * @param array $args
     * @param array $options
     */
    public function createOther(array $args, array $options = array())
    {
        # get description options
        $desc = (isset($options['d'])) ? $options['d'] : "";

        $up_data = array();
        $down_data = array();

        $name_method = "other";

        $this->_save(
            $this->setTemplateMethod(strtolower($name_method), 'create', $up_data),
            $this->setTemplateMethod(strtolower($name_method), 'create', $down_data, "down")
            , $desc
        );
    }

    /**
     * _save
     * @param $up_content
     * @param $down_content
     * @param bool $tag
     * @param $desc
     */
    private function _save($up_content, $down_content, $desc, $tag = false)
    {
        if (!$this->isMulti()) {

            if (empty($desc)) {
                $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
                $desk = "Type Description of migration file. Example: #TASK-124";
                $desc = $dialog->ask($desk . PHP_EOL . $this->color('Description:', \ConsoleKit\Colors::BLUE), "",
                    false);
            }

            if ($tag) {
                $desc = $desc . " #" . $tag;
            }

            $name_migration = $this->getMigrationName();
            $this->saveTemplate($name_migration,
                $this->setTemplate(
                    $name_migration,
                    $up_content,
                    $down_content,
                    $desc,
                    get_current_user()
                ), $tag);

        } else {

            $db = debug_backtrace();
            $this->setMultiHeaders($this->color('>', \ConsoleKit\Colors::YELLOW) . " " . $db[1]['function']);
            $this->setMultiAddReturn($up_content, $tag);
            $this->setMultiDeleteReturn($down_content, $tag);

        }
    }

    /**
     * @return null
     */
    public function getGenerateObject()
    {
        return $this->generateObject;
    }

    /**
     * @param null $generateObject
     */
    public function setGenerateObject($generateObject)
    {
        $this->generateObject = $generateObject;
    }

    /**
     * @return boolean
     */
    public function isMulti()
    {
        return $this->isMulti;
    }

    /**
     * @param boolean $isMulti
     */
    public function setMulti($isMulti)
    {
        $this->isMulti = $isMulti;
    }

    /**
     * @return array
     */
    public function getMultiAddReturn()
    {
        return (array)$this->multiAddReturn;
    }

    /**
     * @param array $multiAddReturn
     */
    public function setMultiAddReturn($multiAddReturn, $type = "add")
    {
        $this->multiAddReturn[$type][] = $multiAddReturn;
    }

    /**
     * @return array
     */
    public function getMultiDeleteReturn()
    {
        return (array)$this->multiDeleteReturn;
    }

    /**
     * @param array $multiDeleteReturn
     */
    public function setMultiDeleteReturn($multiDeleteReturn, $type = "add")
    {
        $this->multiDeleteReturn[$type][] = $multiDeleteReturn;
    }

    /**
     * @return array
     */
    public function getMultiHeaders()
    {
        return $this->multiHeaders;
    }

    /**
     * @param $multiHeaders
     * @internal param array $multiHeders
     */
    public function setMultiHeaders($multiHeaders)
    {
        $this->multiHeaders[] = $multiHeaders;
    }

    /**
     * @return null
     */
    public function getMultiCurrentCommand()
    {
        return $this->multiCurrentCommand;
    }

    /**
     * @param null $multiCurrentCommand
     */
    public function setMultiCurrentCommand($multiCurrentCommand)
    {
        $this->multiCurrentCommand = $multiCurrentCommand;
    }

}
