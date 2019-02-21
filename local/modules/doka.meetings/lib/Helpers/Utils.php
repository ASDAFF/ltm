<?php

namespace Spectr\Meeting\Helpers;

class Utils
{
    public static function removeSpaces($str)
    {
        return str_replace(' ', '_', $str);
    }

    public static function removeSlashes($str)
    {
        return str_replace('/', '_', $str);
    }

    public static function removeStars($str)
    {
        return str_replace('*', '_', $str);
    }

    public static function cleanFolder($path, $t = '1')
    {
        $rtrn = '1';
        if (file_exists($path) && is_dir($path)) {
            $dirHandle = opendir($path);
            while (false !== ($file = readdir($dirHandle))) {
                if ($file != '.' && $file != '..') {
                    $tmpPath = $path.'/'.$file;
                    chmod($tmpPath, 0777);
                    if (is_dir($tmpPath)) {
                        self::cleanFolder($tmpPath);
                    } else {
                        if (file_exists($tmpPath)) {
                            unlink($tmpPath);
                        }
                    }
                }
            }
            closedir($dirHandle);
            if ($t == '1') {
                if (file_exists($path)) {
                    rmdir($path);
                }
            }
        } else {
            $rtrn = '0';
        }

        return $rtrn;
    }

    public static function deleteFile($file)
    {
        @unlink($file);
    }

    public static function writeToLog($data)
    {
        CheckDirPath($_SERVER['DOCUMENT_ROOT'].'/upload/logs/');
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/logs/'.date("j.n.Y").'.log', $data, FILE_APPEND);
    }
}