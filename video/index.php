<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Video");
$title = "";
if(LANGUAGE_ID == "ru")
{
	$title = "ВИДЕО";
}
elseif(LANGUAGE_ID == "en")
{
	$title = "VIDEO";
}
$APPLICATION->SetPageProperty("title", $title);
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
?><?
if(LANGUAGE_ID == "ru")
{
?>
<h2>Общая информация о выставке LTM</h2>
<video controls="controls" poster="/video_files/poster.jpg" width="640">
	<source src="/video_files/day.m4v" type="video/mp4" />
	<source src="/video_files/day.ogv" type="video/ogg" />
	<source src="/video_files/day.webm" type="video/webm" />
	
</video>

<h2>Приём в честь 10-летия выставки LTM</h2>
<video controls="controls" poster="/video_files/poster.jpg" width="640">
	<source src="/video_files/night.m4v" type="video/mp4" />
	<source src="/video_files/night.ogv" type="video/ogg" />
	<source src="/video_files/night.webm" type="video/webm" />
	
</video>

<h2>Участники о выставке LTM</h2>
<video controls="controls" poster="/video_files/poster.jpg" width="640">
	<source src="/video_files/interviews.m4v" type="video/mp4" />
	<source src="/video_files/interviews.ogv" type="video/ogg" />
	<source src="/video_files/interviews.webm" type="video/webm" />
	
</video>

 <?
}
elseif(LANGUAGE_ID == "en")
{
?>
<h2>LTM in general</h2>
<video controls="controls" poster="/video_files/poster.jpg" width="640">
	<source src="/video_files/day.m4v" type="video/mp4" />
	<source src="/video_files/day.ogv" type="video/ogg" />
	<source src="/video_files/day.webm" type="video/webm" />
	
</video>

<h2>LTM 10th Anniversary Party</h2>
<video controls="controls" poster="/video_files/poster.jpg" width="640">
	<source src="/video_files/night.m4v" type="video/mp4" />
	<source src="/video_files/night.ogv" type="video/ogg" />
	<source src="/video_files/night.webm" type="video/webm" />
	
</video>

<h2>Our exhibitors about us</h2>
<video controls="controls" poster="/video_files/poster.jpg" width="640">
	<source src="/video_files/interviews.m4v" type="video/mp4" />
	<source src="/video_files/interviews.ogv" type="video/ogg" />
	<source src="/video_files/interviews.webm" type="video/webm" />
	
</video>



 <?
}?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>