<?php

use ConsoleKit\Colors;

/**
 * =================================================================================
 * Отмена выполненых миграций  [BIM DOWN]
 * =================================================================================
 *
 * - Общая отмена:
 * @example php bim down
 *
 * Отменяет весь список выполненных миграционных классов.
 *
 * - Еденичная отмена:
 * @example php bim down 1423660766
 *
 * Отменяет указанную в праметрах миграцию.
 *
 * - Отмена по временному периоду:
 * @example php bim down --from="29.01.2015 00:01" --to="29.01.2015 23:55"
 *
 * - Отмена по тегу:
 * @example php bim down --tag=iws-123
 *
 * Отменяет все миграции где найден указанный тег в описании.
 *
 * - Логирование:
 * @example php bim down --logging
 *
 * Documentation: https://github.com/cjp2600/bim-core
 * =================================================================================
 */
class DownCommand extends BaseCommand
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
        krsort($list); #по убыванию
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
            $is_filter = false;
            $f_id = false;
            if ((isset($options['id']))) {
                if (is_string($options['id'])) {
                    $f_id = $options['id'];
                } else {
                    $dialog = new \ConsoleKit\Widgets\Dialog($this->console);
                    $f_id = $dialog->ask('Type migration id:', $f_id);
                }
            } else {
                if (isset($args[0])) {
                    if (is_string($args[0])) {
                        $f_id = $args[0];
                    }
                }
            }
            #check tag list
            $filer_tag = (isset($options['tag'])) ? $options['tag'] : false;

            if ($f_id) {
                if (isset ($return_array_apply[$f_id])) {
                    $is_filter = true;
                    $return_array_apply = array($f_id => $return_array_apply[$f_id]);
                } else {
                    if (isset ($return_array_apply[$f_id])) {
                        $logging_output[] = "Migration " . $f_id . " - is already applied";
                        throw new Exception("Migration " . $f_id . " - is already applied");
                    } else {
                        $logging_output[] = "Migration " . $f_id . " - is not found in applied list";
                        throw new Exception("Migration " . $f_id . " - is not found in applied list");
                    }
                }
            }

            # check to tag list
            if ($filer_tag) {
                $this->padding("down migration for tag : " . $filer_tag);
                $newArrayList = array();
                foreach ($return_array_apply as $id => $mig) {
                    if (!empty($mig[3])) {
                        if (in_array(strtolower($filer_tag), $mig[3])) {
                            $newArrayList[$id] = $mig;
                        }
                    }
                }
                if (!empty($newArrayList)) {
                    $is_filter = true;
                    $return_array_apply = $newArrayList;
                } else {
                    $return_array_apply = array();
                }
            }

            if (!$is_filter) {
                $this->askDoOperation((isset($options['force'])),"Are you sure you want to remove all applied migration");
            }

            if (empty($return_array_apply)) {
                $logging_output[] = "Applied migrations list is empty.";
                $this->info("Applied migrations list is empty.");
                if ($logging) {
                    $this->logging($logging_output, "down");
                }
                return false;
            }

            $time_start = microtime(true);
            $this->info(" <- Start revert migration:");
            $this->writeln('');
            foreach ($return_array_apply as $id => $mig) {
                include_once "" . $mig[1] . "";
                if ((method_exists($mig[0], "down"))) {
                    try {
                        # start transaction
                        $DB->StartTransaction();
                        if (false !== $mig[0]::down()) {
                            if (Bim\Db\Entity\MigrationsTable::isExistsInTable($id)) {
                                if (Bim\Db\Entity\MigrationsTable::delete($id)) {
                                    # commit transaction
                                    $DB->Commit();
                                    $this->writeln($this->color("     - revert   : " . $mig[2], Colors::GREEN));
                                    $logging_output[] = "revert   : " . $mig[2];
                                } else {
                                    # rollback transaction
                                    $DB->Rollback();
                                    $logging_output[] = "error   : " . $mig[2] . " - Error delete in migration table";
                                    throw new Exception("Error delete in migration table");
                                }
                            }
                        } else {
                            $this->writeln(Colors::colorize("     - error : " . $mig[2],
                                    Colors::RED) . " " . Colors::colorize("(Method Down return false)",
                                    Colors::YELLOW));
                            $logging_output[] = "error : " . $mig[2] . " - Method Down return false";
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
                                Colors::RED) . " " . Colors::colorize("( " . $debug . "" . $e->getMessage() . " )",
                                Colors::YELLOW));
                        $logging_output[] = "error : " . $mig[2] . " " . $debug . $e->getMessage();
                    }
                }
            }

            $time_end = microtime(true);
            $time = $time_end - $time_start;
            $this->writeln('');
            $this->info(" <- " . round($time, 2) . "s");
            $logging_output[] = "End time - " . round($time, 2) . "s";
            if ($logging) {
                $this->logging($logging_output, "down");
            }
        }  else {
            $this->info('Empty migration');
        }
    }
}