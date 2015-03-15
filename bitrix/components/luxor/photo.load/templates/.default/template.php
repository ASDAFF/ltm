<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<h1>Загрузка фотографий</h1>
<form action="" name="load_photo" method='post' enctype='multipart/form-data'>
    <p>Выберите раздел в фотогалерее в который будут загружены фотографии:</p>
        <p><select name="sect">
                <option value="0">Выберите раздел</option>
            <? foreach ($arResult["SECT"] as $section):?>
                <option value="<?=$section["ID"]?>"><?=$section["NAME"]?></option>
            <? endforeach;?>
        </select></p>

    <p><input name='file[]' type='file' multiple='true' min='1' max='50'/></p>

    <p><input type='submit' value='Загрузить' name="upload"  class="custom-buttom" /></p>


    <?if(isset ($arResult["COUNT_ALL"]) && $arResult["COUNT_ALL"] > 0):?>
    <p>Загружено <?=$arResult["COUNT_SUCCESS"]?> фотографий из <?=$arResult["COUNT_ALL"]?></p>
    <?endif;?>
</form>