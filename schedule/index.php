<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Detailed schedule for LTM exhibition");
$title = "";
if(LANGUAGE_ID == "ru")
{
	$title = "РАСПИСАНИЕ";
}
elseif(LANGUAGE_ID == "en")
{
	$title = "DETAILED SCHEDULE";
}
$APPLICATION->SetPageProperty("title", $title);
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
?> <?
if(LANGUAGE_ID == "ru")
{
?> 
<p>Программа Luxury Travel Mart идентична во всех городах проведения выставки (за исключением весеннего LTM Москва):</p>
 
<p>10:00 – 14:20 — Утренняя сессия. Индивидуальные встречи с участниками выставки по заранее составленному расписанию. Каждая встреча протяженностью 10 минут, по следующей схеме:</p>
 
<p>10:00 - 10:10 / 10:15 - 10:25 / 10:30 - 10:40 / 10:45 - 10:55<br />
11:00 - 11:10 / 11:15 - 11:25 / 11:30 - 11:40 / 11:45 - 11:55 
  <br />
 11:55 - 12:10 — Перерыв на кофе 
  <br />
 12:10 - 12:20 / 12:25 - 12:35 / 12:40 - 12:50 / 12:55 - 13:05<br />13:10 - 13:20 / 13:25 - 13:35 / 13:40 - 13:50 / 13:55 - 14:05 / 14:10 - 14:20</p>
 
<p>Все встречи назначаются через сайт либо мобильное приложение LTM.</p>
 
<p>14:30 – 15:30 — Свободное общение с участниками мероприятия и ланч для гостей и участников.</p>
 
<p>15:30 – 18:00 — Встречи с региональными агентствами по программе Hosted Buyer. Доступ строго по приглашению организаторов. </p>
 
<p>18:30 – 21:00 — Вечерняя сессия и коктейль в непринуждённой атмосфере традиционного семинара, без заранее назначенных встреч.</p>
 
<p>Программа весеннего LTM Москва:</p>
 
<p>1 день — встречи с региональными компаниями с 10:00 до 18:00, с одним перерывом на обед и двумя кофе-паузами. Доступ строго по приглашению организаторов.</p>
 
<p>2 день:</p>
 
<p>10:00 – 14:20 — Утренняя сессия. Индивидуальные встречи с участниками выставки по заранее составленному расписанию. Каждая встреча протяженностью 10 минут, по схеме указанной выше.</p>
 
<p>19:00 – 23:30 — Вечерний приём с развлекательной программой. </p>
 
<p>Доступ на все мероприятия выставки возможен только для зарегистрированных гостей.</p>
 
<p>Иностранные поставщики туристических услуг и отели, а также их представители, не участвующие в Luxury Travel Mart в качестве экспонентов, не допускаются к посещению из-за возможного конфликта интересов с участниками выставок.</p>
 
<p>Данное расписание представлено для ознакомления, изменения в программе возможны. </p>
 <?
}
elseif(LANGUAGE_ID == "en")
{
?> 
<p>The Luxury Travel Mart schedule is similar for all events (except for LTM Moscow Spring):</p>
 
<p>10:00 – 14:20 — Morning session. Individual pre-scheduled appointments with top-buyers. Each appointment will last 10 minutes, scheduled as follows:</p>
 
<p>10:00 - 10:10 / 10:15 - 10:25 / 10:30 - 10:40 / 10:45 - 10:55 
  <br />
 11:00 - 11:10 / 11:15 - 11:25 / 11:30 - 11:40 / 11:45 - 11:55 
  <br />
 11:55 - 12:10 — Coffee break 
  <br />
 12:10 - 12:20 / 12:25 - 12:35 / 12:40 - 12:50 / 12:55 - 13:05
  <br />
 13:10 - 13:20 / 13:25 - 13:35 / 13:40 - 13:50 / 13:55 - 14:05 / 14:10 - 14:20</p>
 
<p>All appointments are scheduled through LTM web-site and/or LTM mobile apps.</p>
 
<p>14:30 – 15:30 — After-session networking and lunch with guests of the morning session.</p>
 
<p>15:30 – 18:00 — Appointments with the hosted buyers. </p>
 
<p>18:30 – 21:00 — Evening session. Workshop and cocktail reception for travel agents, corporate clients and journalists in relaxed atmosphere.</p>
 
<p><b>Program of LTM Moscow Spring:</b></p>
 
<p><b>Day 1:</b></p>
 
<p>10:00 - 18:00 — First day is dedicated to hosted buyers only, with a huge group of hosted buyers from the Russian regions, CIS countries and Baltic States. All appointments are pre-scheduled, with 10 minutes per appointment, 2 coffee-breaks and lunch during the day.</p>
 
<p><b>Day 2:</b></p>
 
<p>10:00 – 14:20 — Morning session. Individual pre-scheduled appointments with top-buyers. Each appointment will last 10 minutes, scheduled as above.</p>
 
<p>14:30 – 15:30 — After-session networking and lunch with guests of the morning session.</p>
 
<p>19:00 – 23:30 — Evening reception with entertainment.</p>
 
<p>Access to all events will only be available to pre-registered visitors. 
  <br />
 Non-participating hoteliers, DMC companies and other hospitality providers will not be permitted to attend the Luxury Travel Mart Moscow as visitors. 
  <br />
 This schedule is for review only, changes in the program are possible.</p>
 <?
}?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>