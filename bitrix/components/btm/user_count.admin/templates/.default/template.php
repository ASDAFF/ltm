<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	if($arResult["TYPE"] == "FORM"){
	?>
	<form action="" method="post" name="count">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="150"><strong>ID (����� �����)</strong></td>
        <td><?=$arResult["USER"]["ID"]?></td>
      </tr>
      <tr>
        <td width="150"><strong>�������������</strong></td>
        <td><?=$arResult["USER"]["NAME"]?> <?=$arResult["USER"]["SURNAME"]?></td>
      </tr>
      <tr>
        <td><strong>��������</strong></td>
        <td><?=$arResult["USER"]["COMPANY"]?></td>
      </tr>
      <tr>
        <td><strong>�����</strong></td>
        <td><?=$arResult["USER"]["ADDRESS"]?>, <?=$arResult["USER"]["CITY"]?>, <?=$arResult["USER"]["COUNTRY"]?>, <?=$arResult["USER"]["PHONE"]?></td>
      </tr>
      <tr>
        <td><strong>����� �����</strong></td>
        <td><input name="pay" type="text" value="<?=$arResult["USER"]["PAY_COUNT"]?>" /></td>
      </tr>
      <tr>
        <td><strong>���������</strong></td>
        <td>
        	<?
            	foreach($arResult["USER"]["REKV"] as $rekv){
					if($rekv["ACTIVE"] == "Y"){
						?><input name="rekv" type="radio" value="<?=$rekv["ID"]?>" checked="checked" /> <?=$rekv["VALUE"]?><br /><?
					}
					else{
						?><input name="rekv" type="radio" value="<?=$rekv["ID"]?>" /> <?=$rekv["VALUE"]?><br /><?
					}
				}
			?>
        </td>
      </tr>
      <tr class="send">
        <td><input type="submit" value="���������� ����" name="count_look"/></td>
        <td><input type="submit" value="��������� ����" name="count_make"/>&nbsp; &nbsp; &nbsp;<input type="submit" value="���������" name="count_save"/></td>
      </tr>
    </table>
        <input name="type" type="hidden" value="form" />
    </form>
	<?
	}
	elseif($arResult["TYPE"] == "SENT"){
	?>
    <br />
    <br />
    <p style="padding-left:10px;">���� ��� ������� ��������� �� ������ <?=$arResult["USER"]['EMAIL']?></p>
    <br />
    <br />
	<?
	}
}
else{
	?>
    <br />
    <br />
    <p style="padding-left:10px;"><?=$arResult["ERROR_MESSAGE"]?></p>
    <br />
    <br />
	<?
}
?>