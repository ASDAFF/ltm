<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div id="exhibition-tab-1">
<?
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/cabinet/" . $exhibCode . "/morning/";
$userId = trim($_REQUEST["UID"]);
$appCode= 1;
$isHB = false;
CModule::IncludeModule("iblock");
$rsExhib = CIBlockElement::GetList(array(), array("IBLOCK_ID" => "15", "CODE" => $exhibCode, "ACTIVE" => "Y"), false, false, array("PROPERTY_APP_ID", "PROPERTY_APP_HB_ID"));
if($arExhib = $rsExhib->Fetch()){
	if($arExhib["PROPERTY_APP_HB_ID_VALUE"] != ""){
		$appCode = $arExhib["PROPERTY_APP_HB_ID_VALUE"];
		$isHB = true;
	}
	else{
		$appCode = $arExhib["PROPERTY_APP_ID_VALUE"];
	}
}

if($_SESSION["USER_TYPE"] == "PARTICIPANT")
{?>
	<div class="morning-session" id="morning-session">

	<?//--> Список табов 2 уровня ?>
	<ul class="pull-overflow sub-tab-session">
		<?if($isHB):?>
			<li class="ui-tabs-active"><a href="<?= $page . "schedule/" . (($userId)?"?UID=".$userId:"")?>" title="">My schedule for the Second Day</a></li>
			<li><a href="<?= $page . "list/" . (($userId)?"?UID=".$userId:"")?>" title="">Guest List for the Second Day</a></li>
		<?else:?>
			<li class="ui-tabs-active"><a href="<?= $page . "schedule/" . (($userId)?"?UID=".$userId:"")?>" title="">My schedule for the morning session</a></li>
			<li><a href="<?= $page . "list/" . (($userId)?"?UID=".$userId:"")?>" title="">Morning session guests</a></li>
		<?endif;?>
	</ul>
		<div id="session-tab-1">
		<? include("participant.php");?>
		</div>
	</div>
<?
}
elseif($_SESSION["USER_TYPE"] == "GUEST")
{?>
  	<div class="morning-session" id="morning-session">
	<?//--> Список табов 2 уровня ?>
	<ul class="pull-overflow sub-tab-session">
		<?if($isHB):?>
			<li class="ui-tabs-active"><a href="<?= $page . "schedule/" . (($userId)?"?UID=".$userId:"")?>" title="">Расписание на сессию 3 марта</a></li>
			<li><a href="<?= $page . "list/" . (($userId)?"?UID=".$userId:"")?>" title="">Список участников</a></li>
		<?else:?>
			<li class="ui-tabs-active"><a href="<?= $page . "schedule/" . (($userId)?"?UID=".$userId:"")?>" title="">Расписание на утреннюю сессию</a></li>
			<li><a href="<?= $page . "list/" . (($userId)?"?UID=".$userId:"")?>" title="">Список участников</a></li>
		<?endif;?>
	</ul>
        <div class="files">
            <ul class="files__items">
                <li class="files__item"><a href="" class="files__link">Скачать план залов (участники в алфавитном порядке)</a></li>
                <li class="files__item"><a href="" class="files__link">Скачать план залов (участники по номеру стола)</a></li>
            </ul>
        </div>
		<div id="session-tab-1">
		<? include("guest.php");?>
		</div>
            

	</div>
<?
}
?>
</div>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>