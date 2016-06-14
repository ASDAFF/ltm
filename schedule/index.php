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
?><?
if(LANGUAGE_ID == "ru")
{
?>
<p>
 <b>Программа LTM Киев и Алматы:</b><br>
</p>
<p>
	 10:00 – 16:55&nbsp;— Индивидуальные встречи с участниками выставки по заранее составленному расписанию. Каждая встреча протяженностью 10 минут.
</p>
<p>
	 Все встречи назначаются через сайт либо мобильное приложение LTM.
</p>
<p>
	 12:55&nbsp;– 14:00&nbsp;— Свободное общение с участниками мероприятия и ланч для гостей и участников.
</p>
<p>
	 19:00 – 21:30 — Вечерний&nbsp;коктейль в непринуждённой атмосфере, без заранее назначенных встреч.<br>
</p>
<p>
	 ___________
</p>
<p>
 <b>Программа осеннего LTM Москва:</b><br>
</p>
<p>
	 10:00 – 14:20 — Утренняя сессия. Индивидуальные встречи с участниками выставки по заранее составленному расписанию. Каждая встреча протяженностью 10 минут, один перерыв на кофе.
</p>
<p>
	 Все встречи назначаются через сайт либо мобильное приложение LTM.
</p>
<p>
	 14:30 – 15:30 — Свободное общение с участниками мероприятия и ланч для гостей и участников.
</p>
<p>
	 15:30 – 17:30 — Встречи с региональными агентствами по программе Hosted Buyer. Доступ строго по приглашению организаторов.&nbsp;
</p>
<p>
	 19:00 – 21:30 — Вечерняя сессия и коктейль в непринуждённой атмосфере, без заранее назначенных встреч.
</p>
<p>
	 ___________<br>
</p>
<p>
 <b>Программа весеннего LTM Москва:</b>
</p>
<p>
 <b>1 день</b>&nbsp;—&nbsp;встречи с региональными компаниями с 10:00 до 18:00, с одним перерывом на обед и двумя кофе-паузами. Доступ строго по приглашению организаторов.
</p>
<p>
 <b>2 день:</b>
</p>
<p>
	 10:00 – 15:50 — Индивидуальные встречи с участниками выставки по заранее составленному расписанию. Каждая встреча протяженностью 10 минут.
</p>
<p>
	 19:00 – 23:30 — Вечерний приём с развлекательной программой.&nbsp;
</p>
<p>
	 Доступ на все мероприятия выставки возможен только для зарегистрированных гостей.
</p>
<p>
	 Иностранные поставщики туристических услуг и отели, а также их представители, не участвующие в Luxury Travel Mart в качестве экспонентов, не допускаются к посещению из-за возможного конфликта интересов с участниками выставок.
</p>
<p>
	 Данное расписание представлено для ознакомления, изменения в программе возможны.
</p>
 <?
}
elseif(LANGUAGE_ID == "en")
{
?>
<p>
 <b>Program of LTM Kiev and Almaty:</b><br>
</p>
<p>
	 10:00 – 16:55&nbsp;— Individual pre-scheduled appointments with top-buyers. Each appointment will last 10 minutes.
</p>
<p>
	 All appointments are scheduled through LTM web-site and/or LTM mobile apps.<br>
</p>
<p>
	 12:55&nbsp;– 14:00 — Mid-session networking and lunch for guests and exhibitors.
</p>
<p>
	 19:00 – 21:30 — Cocktail reception for travel agents, corporate clients and journalists in relaxed atmosphere.
</p>
<p>
	 ___________<br>
</p>
<p>
 <b>Program of LTM Moscow Autumn:</b><br>
</p>
<p>
	 10:00 – 14:20 — Morning session. Individual pre-scheduled appointments with top-buyers. Each appointment will last 10 minutes, one coffee-break.
</p>
<p>
	 All appointments are scheduled through LTM web-site and/or LTM mobile apps.<br>
</p>
<p>
	 14:30 – 15:30 — After-session networking and lunch with guests of the morning session.
</p>
<p>
	 15:30 – 17:30 — Appointments with the hosted buyers.&nbsp;
</p>
<p>
	 19:00 – 21:30 — Evening session. Workshop and cocktail reception for travel agents, corporate clients and journalists in relaxed atmosphere.
</p>
<p>
	 ___________<br>
</p>
<p>
 <b>Program of LTM Moscow Spring:</b>
</p>
<p>
 <b>Day 1:</b>
</p>
<p>
	 10:00 - 18:00&nbsp;—&nbsp;First day is dedicated to hosted buyers only, with a huge&nbsp;group of hosted&nbsp;buyers from the Russian regions, CIS countries and Baltic States. All appointments are pre-scheduled, with 10 minutes per appointment, 2&nbsp;coffee-breaks and lunch during the day.
</p>
<p>
 <b>Day 2:</b>
</p>
<p>
	 10:00 – 15:50 — Individual pre-scheduled appointments with top-buyers. Each appointment will last 10 minutes.
</p>
<p>
	 15:50 – 16:30 — After-session networking and lunch with guests of the morning session.
</p>
<p>
	 19:00 – 01:00 — Evening reception with entertainment.
</p>
<p>
	 ___________<br>
</p>
<p>
 <b>Access to all events will only be available to pre-registered visitors.&nbsp;</b>
</p>
<p>
 <b>
	Non-participating hoteliers, DMC companies and other hospitality providers will not be permitted to attend the Luxury Travel Mart Moscow as visitors.&nbsp;</b>
</p>
<p>
 <b>
	This schedule is for review only, changes in the program are possible.</b>
</p>
 <?
}?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>