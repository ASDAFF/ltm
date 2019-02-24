<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "About LTM GUEST SYSTEM");
$title = "";
if(LANGUAGE_ID == "ru")
{
	$title = "О ПРИЛОЖЕНИИ LTM GUEST SYSTEM";
}
elseif(LANGUAGE_ID == "en")
{
	$title = "ABOUT LTM GUEST SYSTEM";
}
$APPLICATION->SetPageProperty("title", $title);
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
?><?
if(LANGUAGE_ID == "ru")
{
?>
<p>
Luxury Travel Mart делает участие в своих выставках еще более удобным и продуктивным благодаря мобильным приложениям для Android и iOS. С помощью данного приложения гости и участники выставок LTM могут вести расписание встреч на мероприятиях, посмотреть, кто находится рядом на выставке в режиме реального времени, проверить, кто зарегистрировался на выставку, обмениваться сообщениями, и так далее. 
</p>
<p>
Загрузить приложение для iOS <a href="https://appsto.re/ru/wNO-5.i" target="_blank">возможно здесь</a>.
</p>
<p>
Загрузить приложение для Android <a href="https://play.google.com/store/apps/details?id=com.ltm.meetup" target="_blank">возможно здесь</a>.</p>

 <?
}
elseif(LANGUAGE_ID == "en")
{
?>
<p>
Luxury Travel Mart is making participation in their events event more accesible with new mobiles apps for Android and iOS. With a help of these apps, exhibitors and guests are able to manage their schedule, see who is around at LTM in real-time, check who has come to the event and take advantage of many more useful features. 
</p>
<p>
To download the application for iOS, please <a href="https://appsto.re/ru/wNO-5.i" target="_blank">click here</a>.
</p>
<p>
To download the application for Android, please <a href="https://play.google.com/store/apps/details?id=com.ltm.meetup" target="_blank">click here</a>.
</p>

 <?
}?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>