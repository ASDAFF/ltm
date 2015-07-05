<?php
function str_code($str, $passw=""){
    $salt = "Dn8*#2n!9j";
    $len = strlen($str);
    $gamma = '';
    $n = $len>100 ? 16 : 4;
    while( strlen($gamma)<$len ){
        $gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
    }
    return $str^$gamma;
}


function pre($arr, $name)
{
    global $USER;
    $login = $USER->GetLogin();
    $arDevUsers = array("dmitrz", 'prisve', "test2_partc");

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
    global $USER;
    if ($USER->IsAdmin()){
        if(is_array($item)){
            echo '<pre>'; print_r($item); echo '</pre>';
        }else{
            echo $item;
        }
    }
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

?>