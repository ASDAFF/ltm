<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
      ����� ����������,  <?=$arResult["NAME"]?><br />
      � ��� <span><?=$arResult["NEW_APP"]?></span> ���������������� �������� �� ������� � <span><?=$arResult["NEW_MESSAGES"]?></span> ����� ���������
	<?
}
?>