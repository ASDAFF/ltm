<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<h1>�������� ����������</h1>
<form action="" name="load_photo" method='post' enctype='multipart/form-data'>
    <p>�������� ������ � ����������� � ������� ����� ��������� ����������:</p>
        <p><select name="sect">
                <option value="0">�������� ������</option>
            <? foreach ($arResult["SECT"] as $section):?>
                <option value="<?=$section["ID"]?>"><?=$section["NAME"]?></option>
            <? endforeach;?>
        </select></p>

    <p><input name='file[]' type='file' multiple='true' min='1' max='50'/></p>

    <p><input type='submit' value='���������' name="upload"  class="custom-buttom" /></p>


    <?if(isset ($arResult["COUNT_ALL"]) && $arResult["COUNT_ALL"] > 0):?>
    <p>��������� <?=$arResult["COUNT_SUCCESS"]?> ���������� �� <?=$arResult["COUNT_ALL"]?></p>
    <?endif;?>
</form>