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
		<li class="ui-tabs-active"><a href="<?= $page . "schedule/" . (($userId)?"?UID=".$userId:"")?>" title="">Расписание на сессию 1 марта</a></li>
		<li><a href="<?= $page . "list/" . (($userId)?"?UID=".$userId:"")?>" title="">Список участников</a></li>
	</ul>
        <div class="files">
            <ul class="files__items">
                <li class="files__item"><a href="/files/spring-2017/day1_alphabetical.pdf" class="files__link" target="_blank">Скачать план залов (hosted buyers в алфавитном порядке)</a></li>
                <li class="files__item"><a href="/files/spring-2017/day1_by_table.pdf" class="files__link" target="_blank">Скачать план залов (hosted buyers по номеру стола)</a></li>
                <li class="files__item"><a href="/files/spring-2017/day2_alphabetical.pdf" class="files__link" target="_blank">Скачать план залов (участники в алфавитном порядке)</a></li>
                <li class="files__item"><a href="/files/spring-2017/day2_by_table.pdf" class="files__link" target="_blank">Скачать план залов (участники по номеру стола)</a></li>
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