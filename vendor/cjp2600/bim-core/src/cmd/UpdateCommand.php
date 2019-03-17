<?php

use ConsoleKit\Colors;

/**
 * =================================================================================
 * Выполнение миграций [BIM UP]
 * =================================================================================
 *
 * - Общее выполнение:
 * @example:
 *          php bim up
 *
 * Выполняет полный список не выполненых либо ранее отмененных миграционных классов
 * отсортированых по названию (**timestamp**).
 *
 * - Еденичное выполнение:
 * @example php bim up 1423660766
 *
 * Выполняет указанную в праметрах миграцию.
 *
 * - Выполнение по временному периоду:
 * @example php bim up --from="29.01.2015 00:01" --to="29.01.2015 23:55"
 *
 * - Выполнение по тегу:
 * @example php bim up --tag=iws-123
 *
 * Выполняет все миграции где найден указанный тег в описании.
 *
 * - Логирование:
 * @example php bim up --logging
 *
 * Опции:
 *
 *  --from : дата создания миграции "От"
 *  --to : дата создания миграции "До"
 *  --migration_path  : Опциональное указания директории хранения миграций
 *  --logging : Логирование вывода
 *
 * Documentation: https://github.com/cjp2600/bim-core
 * =================================================================================
 */
class UpdateCommand extends BaseCommand
{
    public function execute(array $args, array $options = array())
    {
        global $DB;
        
        # get logging options
        $logging = (isset($options['logging'])) ? true : false;
        $logging_output = array();

        # setUserMigration Path
        $this->migrationPath = (isset($options['migration_path'])) ? $options['migration_path'] : null;

        $list = $this->getDirectoryTree($this->getMigrationPath(), "php");
        ksort($list); # по возрастанию
        if (!empty($list)) {
            foreach ($list as $id => $data) {
                $row = $data['file'];
                $name = $data['name'];

                # check in db
                $is_new = (!$this->checkInDb($id));
                $class_name = "Migration" . $id;

                if ($is_new) {
                    $return_array_new[$id] = array(
                        $class_name,
                        "" . $this->getMigrationPath() . $row . "",
                        $name,
                        $data['tags']
                    );
                } else {
                    $return_array_apply[$id] = array(
                        $class_name,
                        "" . $this->getMigrationPath() . $row . "",
                        $name,
                        $data['tags']
                    );
                }
            }

            # filer
            $f_id = false;
            $is_filter = false;
            if ((isset($options['id']))) {
                $is_filter = true;
                if (is_string($options['id'])) {
                    $f_id = $options['id'];
                } else {
                    $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
                    $f_id = $dialog->ask('Type migration id:', $f_id);
                }
            } else {
                if (isset($args[0])) {
                    $is_filter = true;
                    if (is_string($args[0])) {
                        $f_id = $args[0];
                    }
                }
            }
            #check tag list
            $filer_tag = (isset($options['tag'])) ? $options['tag'] : false;

            if ($f_id) {
                if (isset ($return_array_new[$f_id])) {
                    $return_array_new = array($f_id => $return_array_new[$f_id]);
                } else {
                    if (isset ($return_array_apply[$f_id])) {
                        $logging_output[] = "Migration " . $f_id . " - is already applied";
                        throw new Exception("Migration " . $f_id . " - is already applied");
                    } else {
                        $logging_output[] = "Migration " . $f_id . " - is not found in new migrations list";
                        throw new Exception("Migration " . $f_id . " - is not found in new migrations list");
                    }
                }
            }
            # check to tag list
            if ($filer_tag) {
                $is_filter = true;
                $this->padding("up migration for tag : " . $filer_tag);
                $newArrayList = array();
                foreach ($return_array_new as $id => $mig) {
                    if (!empty($mig[3])) {
                        if (in_array(strtolower($filer_tag), $mig[3])) {
                            $newArrayList[$id] = $mig;
                        }
                    }
                }
                if (!empty($newArrayList)) {
                    $return_array_new = $newArrayList;
                } else {
                    $return_array_new = array();
                }
            }

            if (!$is_filter) {
                $this->askDoOperation((isset($options['force'])));
            }


            if (empty($return_array_new)) {
                $logging_output[] = "New migrations list is empty.";
                $this->info("New migrations list is empty.");
                if ($logging) {
                    $this->logging($logging_output);
                }
                return false;
            }

            $time_start = microtime(true);
            $this->info(" -> Start applying migration:");
            $this->writeln('');
            foreach ($return_array_new as $id => $mig) {
                include_once "" . $mig[1] . "";
                # check bim migration.
                if ((method_exists($mig[0], "up"))) {
                    try {
                        # start transaction
                        $DB->StartTransaction();
                        # call up function
                        if (false !== $mig[0]::up()) {
                            if (!Bim\Db\Entity\MigrationsTable::isExistsInTable($id)) {
                                if (Bim\Db\Entity\MigrationsTable::add($id)) {
                                    # commit transaction
                                    $DB->Commit();
                                    $this->writeln($this->color("     - applied   : " . $mig[2], Colors::GREEN));
                                    $logging_output[] = "applied   : " . $mig[2];
                                } else {
                                    # rollback transaction
                                    $DB->Rollback();
                                    $logging_output[] = "error : " . $mig[2] . " - Add in migration table error";
                                    throw new Exception("add in migration table error");
                                }
                            }
                        } else {
                            $this->writeln(Colors::colorize("     - error : " . $mig[2],
                                    Colors::RED) . " " . Colors::colorize("(Method Up return false)", Colors::YELLOW));
                            $logging_output[] = "error : " . $mig[2] . " - Method Up return false";
                        }
                    } catch (Exception $e) {
                        if ((isset($options['debug']))) {
                            $debug = "[" . $e->getFile() . ">" . $e->getLine() . "] ";
                        } else {
                            $debug = "";
                        }
                        # rollback transaction
                        $DB->Rollback();
                        $this->writeln(Colors::colorize("     - error : " . $mig[2],
                                Colors::RED) . " " . Colors::colorize("( " . $debug . "" . $e->getMessage() . ")",
                                Colors::YELLOW));
                        $logging_output[] = "error : " . $mig[2] . "( " . $debug . "" . $e->getMessage() . " )";
                    }
                }
            }
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            $this->writeln('');
            $this->info(" -> " . round($time, 2) . "s");
            $logging_output[] = "End time - " . round($time, 2) . "s";
            if ($logging) {
                $this->logging($logging_output);
            }
        } else {
            $this->info('Empty migration');
        }
    }


}