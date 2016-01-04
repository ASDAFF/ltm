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
<h2>Заголовок видеоролика</h2>
<video controls="controls" poster="/video_files/file.jpg">
	<source src="/video_files/day.m4v" type="video/mp4" />
	<source src="/video_files/day.ogv" type="video/ogg" />
	<source src="/video_files/day.webm" type="video/webm" />
	
</video>


 <?
}
elseif(LANGUAGE_ID == "en")
{
?>
<h2>Title for video</h2>
<video controls="controls" poster="file.jpg">
	<source src="/video_files/day.m4v" type="video/mp4" />
	<source src="/video_files/day.ogv" type="video/ogg" />
	<source src="/video_files/day.webm" type="video/webm" />
	
</video>


 <?
}?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>