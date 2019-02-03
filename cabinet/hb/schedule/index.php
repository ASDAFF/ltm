<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div id="exhibition-tab-1">
<?

$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/cabinet/" . $exhibCode . "/hb/";
$userId = trim($_REQUEST["UID"]);
$appCode= 1;
if($_SESSION["USER_TYPE"] == "PARTICIPANT")
{?>
	<div class="morning-session" id="morning-session">

	<?//--> Список табов 2 уровня ?>
	<ul class="pull-overflow sub-tab-session">
		<li class="ui-tabs-active"><a href="<?= $page . "schedule/" . (($userId)?"?UID=".$userId:"")?>" title="">My schedule for the First Day</a></li>
		<li><a href="<?= $page . "list/" . (($userId)?"?UID=".$userId:"")?>" title="">Guest List for the First Day</a></li>
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
		<li class="ui-tabs-active"><a href="<?= $page . "schedule/" . (($userId)?"?UID=".$userId:"")?>" title="">Расписание на сессию 28 февраля</a></li>
		<li><a href="<?= $page . "list/" . (($userId)?"?UID=".$userId:"")?>" title="">Список участников</a></li>
	</ul>

    <div class="files">
        <ul class="files__items">
            <li class="files__item"><a href="/files/moscow-spring-2019/Moscow_HB_Program_2019.pdf" class="files__link" target="_blank">Скачать программу для гостя по программе региональных покупателей Hosted Buyers (участие в двух днях выставки, 28 февраля и 1 марта 2019)</a></li>
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