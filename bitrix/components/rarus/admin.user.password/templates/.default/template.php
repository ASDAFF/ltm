<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	if($arResult["MESSAGE"]){
		echo "<p style='color:#ff0000; padding:5px;'>".$arResult["MESSAGE"]."</p>";
	}
	?>
	<form action="" method="post" name="count">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="150"><strong>ID</strong></td>
        <td><?=$arResult["ID"]?></td>
      </tr>
      <tr>
        <td width="150"><strong>Логин</strong></td>
        <td><?=$arResult["LOGIN"]?></td>
      </tr>
      <tr>
        <td width="150"><strong>Представитель</strong></td>
        <td><?=$arResult["NAME"]?> <?=$arResult["LAST_NAME"]?></td>
      </tr>
      <tr>
        <td><strong>Компания</strong></td>
        <td><?=$arResult["WORK_COMPANY"]?></td>
      </tr>
      <tr>
        <td><strong>Старый пароль</strong></td>
        <td><?=$arResult["PASSWORD"]?></td>
      </tr>
      <tr>
        <td><strong>Новый пароль</strong></td>
        <td><input name="password" type="text" value="" autocomplete="off"/></td>
      </tr>
      <tr class="send">
        <td>&nbsp;</td>
        <td><input type="submit" value="Сохранить" name="pass_save"/></td>
      </tr>
    </table>
        <input name="type" type="hidden" value="form" />
    </form>
	<?
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