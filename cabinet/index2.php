<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
?>

<?php //Общиц контейнер для ЛК?>
<div class="exhibition-block rus">
	
	<?php //-->Табы конференций?>
		<div class="exhibition-list pull-overflow">
			<ul>
				<li><a href="#" title="">LTM Moscow 2014</a></li>
				<li><a href="#" title="">LTM Baku 2014</a></li>
				<li><a href="#" title="">LTM Kiev 2014.</a></li>
				<li class="active"><a href="" title="">LTM Almaty 2014.</a></li>
				<li><a href="" title="">LTM Moscow 2014.</a></li>
				<li><a href="" title="">LTM Moscow 2014.</a></li>
				<li><a href="" title="">LTM Moscow 2014.</a></li>
			</ul>
		</div>
	<?php //<--Табы конференций?>
	
	
	<?php //--> Заголовки?>
		<h2>LUXURY TRAVEL MART MOSCOW SPRING’ 2014, 13 March</h2>
		<div class="sub-headline">The Ritz-Carlton Moscow</div>
	<?php //<-- Заголовки?>
	
	
	<?php //--> Общие блок для табов 1 уровня ?>
	<div id="exhibition-session" class="exhibition-session">
	
		<?//--> Табы 1 уровня ?>
			<ul class="pull-overflow main-tab">
				<li><a href="#exhibition-tab-1" title="">Программа и инфо</a></li>
				<li><a href="#exhibition-tab-2" title="">Утренняя сессия</a></li>
				<li><a href="#exhibition-tab-5" title="">Мои сообщения (19)</a></li>
				<li><a href="#exhibition-tab-3" title="">Каталог участников</a></li>
			</ul>
		<?//<-- Табы 1 уровня ?>
		
		<?//--> Содержимое таба 1 ?>
		<div id="exhibition-tab-1">
			<p>Телефон организаторов в Москве: <b>+7 495 111 11 11</b>,<br>пожалуйста, обращайтесь по любым вопросам</p>
			
			<h3>ПРОГРАММА МЕРОПРИЯТИЯ</h3>
			
			<p>24 сентября (вторник), 2013г. <br> Отель Интерконтиненталь, Киев, 10:00 – 21:00</p>
			
			<p><b>Место проведения:</b></p>
			
			<p>Отель Интерконтиненталь Киев, улица Большая Житомирская, дом 2А</p>
			
			<p>Залы "Grand Ballroom" и "Conference Hall" – минус второй (конференц) этаж отеля и Meeting Room, второй этаж отеля.</p>
			
			<p>Гардероб для гостей располагается при входе в залы.</p>
			
			<p>Регистрация гостей:</p>
			
			<p>Начало регистрации гостей утренней сессии в 09:30. При входе в залы будут находиться стойки регистрации "Зарегистрированные гости".</p>
			
			<p>Для входа в залы необходимо предъявить именной бейдж, который зарегистрированные гости смогут получить на стойках регистрации. Все гости мероприятия на стойках регистрации смогут также получить электронный каталог участников Luxury Travel Mart и план-схему расположения рабочих мест участников на утренней сессии.</p>
			
			<p>Программа:</p>
			
			<p>10:00 – 14:20 Рабочие встречи согласно предварительно составленному на www.kiev.luxurytravelmart.ru расписанию. Продолжительность каждой встречи 10 минут, 5 минут на переход от участника к участнику. Начало и окончание встречи – специальное звуковое оповещение.</p>
			
			<p>Просим вас строго придерживаться расписания встреч и обязательно посещать все запланированные встречи.</p>
			
			<p>11:55 – 12:10 Перерыв на кофе. Фойе конференц-этажа.</p>
			
			<p>14:30 – 15:30 Ланч для участников и гостей утренней сессии. Возможность для неформального общения. Фойе конференц-этажа.</p>
			
			<p>15:30 – 18:00 Сессия для региональных агентств, по приглашению организаторов.</p>
			
			<p>18:30 Регистрация гостей вечерней сессии.</p>
			
			<p>18:30 – 21:00 Вечерняя сессия, встречи с участниками мероприятия без заранее назначенных расписаний (зал Grand Ballroom Hall).</p>
				
			<p>Рабочий язык мероприятия – английский. Просим иметь при себе достаточное количество визитных карточек на английском языке.</p>
			
			<p>Посетители без визитных карточек на мероприятие допускаться не будут.</p>
			
			<h3>ФАЙЛЫ ДЛЯ СКАЧИВАНИЯ</h3>
			
			<ul>
				<li><a href="" title="">План расположения участников на утренней сессии</a></li>
				<li><a href="" title="">План расположения участников на вечерней сессии</a></li>
				<li><a href="" title="">Список участников с указанием расположения</a></li>
			</ul>
			
		</div>
		<?//<-- Содержимое таба 1 ?>
		
		<?//--> Содержимое таба 2?>
		<div id="exhibition-tab-2">
			<?// Табы 2 уровня ?>
			
			<div class="morning-session" id="morning-session">
			
				<?//--> Список табов 2 уровня ?>
				<ul class="pull-overflow sub-tab-session">
					<li><a href="#session-tab-1" title="">Расписание на утреннюю сессию</a></li>
					<li><a href="#session-tab-2" title="">Список участников</a></li>
				</ul>
				<?//<--Список табов 2 уровня ?>
				
				<?//-->Контейнер содержимого табы 2 уровня ?>
				<div id="session-tab-1">
				
					<?//-->Таблица   ?>
					<? //include("shedule.php");?>
					<?//<--Таблица   ?>
					
					<div class="pull-overflow generate-file">
						<div class="pull-left">
							<a href="" title="">Создать список пожеланий PDF</a>
						</div>
						<div class="pull-right">
							<a href="" title="">Сформировать расписание в PDF</a>
						</div>
					</div>
					
					<div class="request-guests">
						<div>Здесь вы можете запросить только тех участникой, чьи расписания уже заполнены</div>
						<table class="section-request">
							<tr>
								<td class="appointments">
									<div class="wish-list">Вы также хотели бы встретиться с</div>
									<form action="">
										<table class="morning-time">
											<tr>
												<th>№</th>
												<th>Компания</th>
											</tr>
											<tr>
												<td>1</td>
												<td>Mandarin Oriental Barcelona</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Lorem ipsum dolor sit amet.</td>
											</tr>
											<tr>
												<td>7</td>
												<td>Lorem ipsum dolor sit amet.</td>
											</tr>
										</table>
										<div class="send-request send"><a href="" title="">Отправить запрос</a></div>
										
										<select name="" id="">
											<option value="">Выберете компанию</option>
										</select>
									</form>
								</td>
								<td>
									<div class="wish-list">С вами хотели бы встретиться следующие участники</div>
									<table class="morning-time">
										<tr>
											<th>№</th>
											<th>Компания</th>
										</tr>
										<tr>
											<td>1</td>
											<td>Mandarin Oriental Barcelona</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
					
				</div>
				
				<?//-->Содержимое таба 2 уровня 2 вкладка ?>
				<div id="session-tab-2">
					<div class="pull-overflow sorting-company">
						<div class="pull-left">
							<ul>
								<li><a href="" title="">По странам</a></li>
								<li><a href="" title="">По виду деятельности</a></li>
								<li><a href="" title="">По свободному времени</a></li>
								<li><a href="" title="">Все</a></li>
							</ul>
						</div>
						<div class="pull-right">
							<div class="alphabet">
								<a href="" title="">#</a>
								<a href="" title="">A</a>
								<a href="" title="">B</a>
								<a href="" title="">C</a>
								<a href="" title="">D</a>
								<a href="" title="">E</a>
								<a href="" title="">F</a>
								<a href="" title="">G</a>
								<a href="" title="">H</a>
								<a href="" title="">I</a>
								<a href="" title="">J</a>
								<a href="" title="">K</a>
								<a href="" title="">L</a>
								<a href="" title="">M</a>
								<a href="" title="">N</a>
								<a href="" title="">O</a>
								<a href="" title="">P</a>
								<a href="" title="">Q</a>
								<a href="" title="">R</a>
								<a href="" title="">S</a>
								<a href="" title="">T</a>
								<a href="" title="">U</a>
								<a href="" title="">V</a>
								<a href="" title="">W</a>
								<a href="" title="">X</a>
								<a href="" title="">Y</a>
								<a href="" title="">Z</a>
							</div>
						</div>
					</div>
					
					<table class="sorting-company">
						<tr>
							<th>Компания</th>
							<th>Представитель</th>
							<th>Написать</th>
							<th class="free-slots">Free slots</th>
							<th>Запрос</th>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="contact"><a href="" title="">Написать<br>сообщение</a></td>
							<td class="free-slots">
								<select name="" id="">
									<option value="">10:00 – 10:10</option>
								</select>
							</td>
							<td class="request"><a href="" title="">Отправить<br>запрос</a></td>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="contact"><a href="" title="">Написать<br>сообщение</a></td>
							<td class="free-slots">
								<select name="" id="">
									<option value="">10:00 – 10:10</option>
								</select>
							</td>
							<td class="request"><a href="" title="">Отправить<br>запрос</a></td>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="contact"><a href="" title="">Написать<br>сообщение</a></td>
							<td class="free-slots">
								<select name="" id="">
									<option value="">10:00 – 10:10</option>
								</select>
							</td>
							<td class="request"><a href="" title="">Отправить<br>запрос</a></td>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="contact"><a href="" title="">Написать<br>сообщение</a></td>
							<td class="free-slots">
								<select name="" id="">
									<option value="">10:00 – 10:10</option>
								</select>
							</td>
							<td class="request"><a href="" title="">Отправить<br>запрос</a></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<?php //-->Cодержимое таба 3 ?>
			<div id="exhibition-tab-3">
				<div class="hosted-session">
					<h4>Hosted Buyers Session details</h4>
					<p>Time: 15:30 – 18:00</p>
					<p>Place: Conference Hall I & II.</p>
					<p>Format: Buyers will be seated, and exhibitors will move from one buyer to another, without pre- scheduled appointments. 5 minutes strictly for each appointment; at the end of each appointment a bell will ring. Please respect other exhibitors and move forward to another buyer immediately after the bell.</p>
					<p>Please note that if you are sharing space with another hotel or colleague, you must attend all meetings with the hosted buyers together, no separate appointments are allowed in this case.</p>
					<p>Click <a href="" title="">here</a> to download Hosted Buyers floor plan, on which you will see how the tables are assigned in all halls. If you know where the people you would like to see are located, this will maximize your benefit from the Hosted Buyers session.</p>
				</div>
				<div class="morning-session">
					<div class="pull-overflow sorting-company">
						<div class="pull-left">
							<ul>
								<li><a href="" title="">By country of interest</a></li>
								<li><a href="" title="">By city of origin</a></li>
								<li><a href="" title="">By available slots</a></li>
								<li><a href="" title="">All</a></li>
							</ul>
						</div>
						<div class="pull-right">
							<div class="alphabet">
								<a href="" title="">#</a>
								<a href="" title="">A</a>
								<a href="" title="">B</a>
								<a href="" title="">C</a>
								<a href="" title="">D</a>
								<a href="" title="">E</a>
								<a href="" title="">F</a>
								<a href="" title="">G</a>
								<a href="" title="">H</a>
								<a href="" title="">I</a>
								<a href="" title="">J</a>
								<a href="" title="">K</a>
								<a href="" title="">L</a>
								<a href="" title="">M</a>
								<a href="" title="">N</a>
								<a href="" title="">O</a>
								<a href="" title="">P</a>
								<a href="" title="">Q</a>
								<a href="" title="">R</a>
								<a href="" title="">S</a>
								<a href="" title="">T</a>
								<a href="" title="">U</a>
								<a href="" title="">V</a>
								<a href="" title="">W</a>
								<a href="" title="">X</a>
								<a href="" title="">Y</a>
								<a href="" title="">Z</a>
							</div>
						</div>
					</div>
					
					<table class="sorting-company">
						<tr>
							<th>Company</th>
							<th>Representative</th>
							<th>Contact</th>
							<th class="free-slots">Free slots</th>
							<th>Request</th>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="contact"><a href="" title="">Send<br> a message</a></td>
							<td class="free-slots">
								<select name="" id="">
									<option value="">10:00 – 10:10</option>
								</select>
							</td>
							<td class="request"><a href="" title="">Send <br>a request</a></td>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="contact"><a href="" title="">Send<br> a message</a></td>
							<td class="free-slots">
								<select name="" id="">
									<option value="">10:00 – 10:10</option>
								</select>
							</td>
							<td class="request"><a href="" title="">Send <br>a request</a></td>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="contact"><a href="" title="">Send<br> a message</a></td>
							<td class="free-slots">
								<select name="" id="">
									<option value="">10:00 – 10:10</option>
								</select>
							</td>
							<td class="request"><a href="" title="">Send <br>a request</a></td>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="contact"><a href="" title="">Send<br> a message</a></td>
							<td class="free-slots">
								<select name="" id="">
									<option value="">10:00 – 10:10</option>
								</select>
							</td>
							<td class="request"><a href="" title="">Send <br>a request</a></td>
						</tr>
					</table>
				</div>
			</div>
		<?php //<--Содержание таба 3 ?>
		
		<?php //-->Cодержимое таба 4 ?>
			<div id="exhibition-tab-4" class="evening-session" style="display: none;">
				<div class="morning-session">
					<div class="pull-overflow sorting-company">
						<div class="pull-left">
							<ul>
								<li><a href="" title="">By city of origin</a></li>
								<li><a href="" title="">All</a></li>
							</ul>
						</div>
						<div class="pull-right">
							<div class="alphabet">
								<a href="" title="">#</a>
								<a href="" title="">A</a>
								<a href="" title="">B</a>
								<a href="" title="">C</a>
								<a href="" title="">D</a>
								<a href="" title="">E</a>
								<a href="" title="">F</a>
								<a href="" title="">G</a>
								<a href="" title="">H</a>
								<a href="" title="">I</a>
								<a href="" title="">J</a>
								<a href="" title="">K</a>
								<a href="" title="">L</a>
								<a href="" title="">M</a>
								<a href="" title="">N</a>
								<a href="" title="">O</a>
								<a href="" title="">P</a>
								<a href="" title="">Q</a>
								<a href="" title="">R</a>
								<a href="" title="">S</a>
								<a href="" title="">T</a>
								<a href="" title="">U</a>
								<a href="" title="">V</a>
								<a href="" title="">W</a>
								<a href="" title="">X</a>
								<a href="" title="">Y</a>
								<a href="" title="">Z</a>
							</div>
						</div>
					</div>
					
					<table class="sorting-company">
						<tr>
							<th>Company</th>
							<th>Representative</th>
							<th>Collegues</th>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="collegues">John Patterson-Sounwell<br>John Patterson-Sounwell</td>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="collegues">John Patterson-Sounwell</td>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="collegues">John Patterson-Sounwell</td>
						</tr>
						<tr>
							<td class="company">
								<a href="" title="">Starwoods Hotels and Resorts</a>
								<div>Worldwide</div>
							</td>
							<td class="representative">John Patterson-Sounwell</td>
							<td class="collegues">John Patterson-Sounwell</td>
						</tr>
					</table>
				</div>
			</div>
		<?php //<--Содержание таба 4 ?>
		
		<?php //-->Cодержимое таба 5 ?>
			<div id="exhibition-tab-5" class="message-box">
				<div id="message-box-function">
					<ul class="message-list-tab pull-overflow">
						<li><a href="#message-tab-1" title="Входящие">Входящие</a></li>
						<li><a href="#message-tab-2" title="Отправленные">Отправленные</a></li>
						<li><a href="#message-tab-3" title="Написать письмо">Написать письмо</a></li>
						<li><a href="#message-tab-4" title="Связаться с организаторами">Связаться с организаторами</a></li>
					</ul>
					<div class="reed-letter pull-overflow">
					<form action="">
						<div class="head-letter pull-overflow">
							<div class="pull-left contact-info">
								<div>John Tudesky</div>
								<div><a href="" title="">Starwoods Hotels & Resorts</a></div>
							</div>
							<div class="pull-right">
								<div class="date">2014, 12 Feb. 9:20 PM</div>
							</div>
							<div class="pull-overflow theme"><b>New proposals and terms of cooperation with our regular customers to our hotels</b></div>
						</div>
						<div class="message-text">
							Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium animi minus excepturi sint voluptas corporis quas. Harum dolor officiis maxime qui dignissimos praesentium rem ducimus at corporis illum. Veniam quibusdam atque at molestias ducimus sit itaque pariatur officia consectetur repellendus architecto rem consequatur molestiae maiores fugit recusandae animi laudantium consequuntur labore aliquid. Ut qui amet ab omnis expedita cupiditate corrupti cum numquam facere eum. Ipsam nisi dolore odio enim quia a dicta ea reprehenderit nihil earum aliquid esse odit natus fuga asperiores facilis blanditiis cum debitis harum neque iure quibusdam deleniti. Eaque necessitatibus minus aliquid porro adipisci. Nesciunt id dicta ducimus sequi est obcaecati laborum pariatur. Dolor quas rerum laudantium repellat aliquid est necessitatibus! Quod explicabo dolorum hic modi dolor doloremque sequi velit voluptates aut officia odio aspernatur soluta placeat sed numquam asperiores quo ratione earum provident ipsa culpa autem nesciunt magnam totam aperiam tempore ex nulla laudantium tempora voluptatibus eum amet alias eius! Id corporis praesentium exercitationem quisquam repellat soluta tempora velit molestias ea fugiat iste odio explicabo impedit quasi et ratione accusantium sunt temporibus sapiente voluptatibus nihil totam possimus omnis dolores nostrum cupiditate laborum ad ullam alias earum nemo animi deserunt quae aliquam placeat ducimus sit est voluptate perspiciatis perferendis suscipit nisi eos ut provident nulla rerum! Animi distinctio quia sit quas molestias doloremque sequi rem odio maiores officia itaque inventore temporibus ratione nemo illum officiis dolorum dolore atque incidunt ad id perferendis at impedit commodi optio? Eligendi autem distinctio blanditiis cupiditate a iure architecto id suscipit repellendus.	
						</div>
						<div class="send">
							<a title="" href="">Ответить</a> 
							<a title="" href="">Переслать</a> 
						</div>
					</form>
					</div>
					<div id="message-tab-1" class="inbox morning-session">
						<table class="morning-time inbox-list">
							<tr>
								<th>Тема</th>
								<th>Компания</th>
								<th>Отправитель</th>
								<th>Дата</th>
							</tr>
							<tr>
								<td>New proposals and terms of cooperation with our regular customers to our hotels</td>
								<td class="company"><a href="" title="">Starwoods Hotels & Resorts</a></td>
								<td>John Tudesk</td>
								<td class="date">2014, 12 Feb. 9:20 PM</td>
							</tr>
							<tr>
								<td class="unread">New proposals and terms of cooperation with our regular customers to our hotels</td>
								<td class="company"><a href="" title="">Starwoods Hotels & Resorts</a></td>
								<td>John Tudesk</td>
								<td class="date">2014, 12 Feb. 9:20 PM</td>
							</tr>
							<tr>
								<td>New proposals and terms of cooperation with our regular customers to our hotels</td>
								<td class="company"><a href="" title="">Starwoods Hotels & Resorts</a></td>
								<td>John Tudesk</td>
								<td class="date">2014, 12 Feb. 9:20 PM</td>
							</tr>
						</table>
					</div>
					
					<div id="message-tab-2" class="sendbox morning-session">
						<table class="morning-time inbox-list">
							<tr>
								<th>Тема</th>
								<th>Компания</th>
								<th>Отправитель</th>
								<th>Дата</th>
							</tr>
							<tr>
								<td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis nulla.</td>
								<td  class="company"><a href="" title="">Starwoods Hotels & Resorts</a></td>
								<td>John Tudesk</td>
								<td  class="date">2014, 12 Feb. 9:20 PM</td>
							</tr>
							<tr>
								<td class="unread">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis cupiditate repellendus ratione delectus cum quos odit recusandae obcaecati optio doloremque.</td>
								<td  class="company"><a href="" title="">Starwoods Hotels & Resorts</a></td>
								<td>John Tudesk</td>
								<td class="date">2014, 12 Feb. 9:20 PM</td>
							</tr>
							<tr>
								<td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente minima voluptatibus deserunt dolore dignissimos inventore repellendus vitae aperiam ipsam quibusdam quasi odit illo nostrum reprehenderit quidem at voluptate asperiores officiis.</td>
								<td  class="company"><a href="" title="">Starwoods Hotels & Resorts</a></td>
								<td>John Tudesk</td>
								<td class="date">2014, 12 Feb. 9:20 PM</td>
							</tr>
						</table>
					</div>
					
					<div id="message-tab-3" class="new-message">
						<form action="">
							<input type="text" placeholder="Тема сообщения">
							<textarea>Текст сообщения</textarea>
							<div class="send"><input type="submit" value="Отправить" /></div>
						</form>
					</div>
					<div id="message-tab-4" class="new-message">
						<form action="">
							<input type="text" placeholder="Тема сообщения">
							<textarea>Текст сообщения </textarea>
							<div class="send"><input type="submit" value="Отправить" /></div>
						</form>
					</div>
				</div>
			</div>
		<?php //<--Содержание таба 5 ?>
	</div>
	<?php //<-- Табы 1 уровня ?>
	
</div>

<?php //-->Редактирование профиля ?>
<div class="edit-profil pull-overflow">
	<form action="">
		<div class="profil pull-overflow">
		
			<div class="pull-overflow headline">КОЛЛЕГА №1 НА ВЕЧЕРНЮЮ СЕССИЮ</div>
			
			<div class="pull-left profil-photo">
			<div class="member">
				<a href="" title=""><img src="http://placehold.it/108x108"></a>
			</div>
				
				<a href="##" title="">Загрузить фото</a>
			</div>
			
			<div class="profil-field pull-left">
				<div class="form-group">
					<label class="control-label" for="inputdemo">Имя</label>
					<div class="data-control">
						<input type="text" placeholder="John" id="inputdemo" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="inputdemo">Фамилия</label>
					<div class="data-control">
						<input type="text" placeholder="Patterson" id="inputdemo" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="inputdemo">Должность</label>
					<div class="data-control">
						<input type="text" placeholder="Hello" id="inputdemo" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="inputdemo">E-mail</label>
					<div class="data-control">
						<input type="email" placeholder="Commercial Director" id="inputdemo" class="form-control">
					</div>
				</div>
			</div>
			
		</div>
		<div class="profil pull-overflow">
		
			<div class="pull-overflow headline">КОЛЛЕГА НА УТРЕННЮЮ СЕССИЮ</div>
			
			<div class="pull-left profil-photo">
			<div class="member">
				<a href="" title=""><img src="http://placehold.it/108x108"></a>
			</div>
				
				<a href="##" title="">Загрузить фото</a>
			</div>
			
			<div class="profil-field pull-left">
				<div class="form-group">
					<label class="control-label" for="inputdemo">Имя</label>
					<div class="data-control">
						<input type="text" placeholder="John" id="inputdemo" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="inputdemo">Фамилия</label>
					<div class="data-control">
						<input type="text" placeholder="Patterson" id="inputdemo" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="inputdemo">Должность</label>
					<div class="data-control">
						<input type="text" placeholder="Hello" id="inputdemo" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="inputdemo">E-mail</label>
					<div class="data-control">
						<input type="email" placeholder="Commercial Director" id="inputdemo" class="form-control">
					</div>
				</div>
			</div>
		</div>
		
		<div class="send-change send">
			<input type="submit" value="Сохранить изменения" />
		</div>
	</form>
	<div class="signature pull-overflow">
		<span>Если кто-то из ваших коллег хочет отдельные от вас встречи, то ему необходимо пройти процесс регистрации.</span>
		<div>При загрузке фотографий учитывайте, что файлы должны быть не более 2mb. и содержать лицо участника крупным планом.</div>
	</div>
</div>
<?php //<--Редактирование профиля ?>

<div class="create-page">
	<div class="create-page-result">
		Your information was sent to organizers for approving. 
		It will take no more than 5 days. If you want something changed or added, just write about it
	</div>
	
	<div class="creating-page">
		<div class="pull-overflow create-company">
			<div class="pull-left company-info data-control">
				<div class="title">Name of your company</div>
				<input type="text" name="" id="" class="form-control" value="Company name" disabled=disabled />
			</div>
			<div class="pull-left company-info data-control">
				<div class="title">Select area of business</div>
			<input type="text" name="" id="" class="form-control" value="Hotels" disabled=disabled />
			</div>
			<div class="pull-left company-info">
				<div class="title">Jpg only!</div>
				<label class="button-dark ltm-btn" >upload logo<input type="file" name="" id="" value=""/></label>
			</div>
		</div>
		
		<div class="description">
			<div class="title"> Please enter a description of your company and service</div>
			<textarea name="" id="" cols="30" rows="10"></textarea>
			<div class="title">	Please upload a minimum of 6 (maximum 12) photos of your company/hotel. The maximum size of each photo is 3mb. Don’t forget &mdash; the better the quality of your photos, the better the impression of your company/hotel.</div>
			<label class="button-dark ltm-btn" >upload photos<input type="file" name="" id="" value=""/></label>
		</div>
	
		<div class="pull-overflow city-link">
			<div class="pull-left company-info">
				<div class="title">Select the country of your business</div>
				<select name="" id="" class="inputselect">
					<option value="">1</option>
					<option value="">2</option>
					<option value="">3</option>
					<option value="">4</option>
				</select>
			</div>
			<div class="pull-left company-info data-control">
				<div class="title">Web</div>
				<input type="text" name="" id="" value="http://" class="form-control" />
			</div>
		</div>
		<input type="submit" value="send to organizers" class="button-green ltm-btn" />
	</div>
</div>
 <script>
$(function() {
$( "#exhibition-session,#morning-session,#message-box-function" ).tabs();
});

$(document).ready(function(){
	/*var mainWidth = $(".exhibition-list").width();
	var activeListWidth = $(".exhibition-list li.active").width();
	var countWidth = $(".exhibition-list li").length-1;
	var result = mainWidth - activeListWidth -10;
	result = result/countWidth;
	$(".exhibition-list li").css("width",result);
	$(".exhibition-list li.active").css("width",activeListWidth);*/
 });
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>