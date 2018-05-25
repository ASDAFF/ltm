<?php

function passCode($str, $passw=""){
    $salt = "Dn82n9j";
    $len = strlen($str);
    $gamma = '';
    $n = $len>100 ? 16 : 4;
    while( strlen($gamma)<2*$len ){
        $gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
    }
    return $str^$gamma;
}
function passExcelCode($str, $passw=""){
    $salt = "Dn82n9j";
    $len = mb_strlen($str);
    $gamma = '';
    $n = $len>100 ? 16 : 4;
    while( mb_strlen($gamma)<2*$len ){
        $gamma .= mb_substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
    }
    return $str^$gamma;
}
function makePassCode($passw=""){
    return base64_encode(passCode($passw, 'luxoran'));
}
function makePassDeCode($passw=""){
    return passCode(base64_decode($passw), "luxoran");
}
function makePassExcelDeCode($passw=""){
    return passExcelCode(base64_decode($passw), "luxoran");
}

function pre($arr, $name = NULL)
{
    global $USER;
    $login = $USER->GetLogin();
    $arDevUsers = array("dmitrz", 'admin', "test2_partc");

    if(isset($name) && $name == $login)
    {
        echo "<pre>";
        echo htmlspecialcharsBx(print_r($arr,true));
        echo "</pre>";
    }
    elseif(in_array($login, $arDevUsers))
    {
        echo "<pre>";
        echo htmlspecialcharsBx(print_r($arr,true));
        echo "</pre>";
    }
}

function c($item){
    echo '<pre>';
    print_r($item);
    echo '</pre>';
}


function translit_file_name($path)
{
    //поулчаем имя файла
    $path = bx_basename($path);
    $pos = strrpos($path, '.');

    $name = substr($path, 0, $pos);
    $ext = substr($path, $pos);

    $name = CUTil::translit($name, "ru");

    return $name.$ext;
}

/**
 * @param $source - исходный файл \ папка
 * @param $fileName - имя архива
 * @return bool
 * Метод архивации при помощи стандартного ZipArchive
 */
function MakeZipArchive($source, $fileName)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }
    $zip = new ZipArchive();
    if (!$zip->open($fileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)) {
        return false;
    }
    $source = str_replace('\\', '/', realpath($source));
    if (is_dir($source) === true){
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($files as $file){
            $file = str_replace('\\', '/', realpath($file));
            if (is_file($file) === true){
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
    return $zip->close();
}
