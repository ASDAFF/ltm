<h1>Алгоритм работы с тестовым (dev.luxurytravelmart.ru) и рабочим (luxurytravelmart.ru) сайтами и ветками:</h1>

<p>На рабочем сайте luxurytravelmart.ru активная ветка ВСЕГДА master. На тестовом сайте dev.luxurytravelmart.ru активна обычно ветка dev.</p>
<p>На сайте hl.luxurytravelmart.ru делаем новую ветку hl (из мастер) и работаем на этом поддомене именно на ней (т.е. создаются новые ветки уже из hl)<p>

<h2>При работе по переходу на HL-ИБ</h2>
<p>Все работы ведутся на отдельном поддомене hl.luxurytravelmart.ru. При работе в git на этом поддомене основной веткой является ветка hl, а другие ветки создаются из неё и сливаются в неё же.</p>

<p>Т.о. работа происходит по следующему сценарию:<br>
<b>На локальном сервере</b> <br>
git checkout hl <br>
[git~hl] git checkout -b [new_branch] - создаем новую ветку из ветки hl<br>
проводим тесты, исправляем ошибки и т.п.<br>
[git~new_branch] git commit -am "New commit" - вносим изменения, коммитим<br> 
[git~new_branch] git checkout hl - возврат на hl<br>
[git~hl] git merge [new_branch] - мержим созданную ветку в hl<br>
[git~hl] git push origin hl - отправка ветки hl в удаленный репозиторий<br></p>

<p><b>На удаленном сервере:</b></p>
<p>cd /home/bitrix/ext_www/hl.luxurytravelmart.ru<br>
git status {проверяем, что на ветке hl}, если нет - git checkout hl<br>
git pull origin hl</p>

<h2>При работе над основным кодом</h2>

<p><b>На локальном сервере</b> <br>
	git checkout master <br>
	git pull origin master <br>
	git checkout -b {имя новой ветки} (все новые ветки создаем из master) <br>
	вносим изменения в ветке <br>
	git add {имя_файла}<br>
	git commit -m "коммент к изменению в ветке" <br>
	git checkout dev <br>
	git pull origin dev <br>
	git merge {имя новой ветки} <br>
	git push origin dev
</p>

<p><b>На удаленном сервере:</b> <br>
	cd /home/bitrix/ext_www/dev.luxurytravelmart.ru/ - переходим на тестовый сайт <br>
	git checkout dev (по идее мы уже итак должны быть в ветке dev в данной папке, но на всякий случай) <br>
	git pull origin dev <br>
	После этого проводятся тесты на dev.luxurytravelmart.ru. Если все ок, тогда:</p>

<p><b>На локальном сервере:</b> <br>
	git checkout master <br>
	git pull origin master <br>
	git merge {имя новой ветки} <br>
	git push origin master</p>

<p><b>На удаленном сервере:</b> <br>
	cd /home/bitrix/www/ <br>
	git checkout master - опять же, мы на рабочем сайте ВСЕГДА должны быть на ветке master <br>
	git pull origin master</p>

<p>С уважением, <br>
	Дмитрий Каплунов.</p>