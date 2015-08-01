<h1>Алгоритм работы с тестовым и рабочим сайтами и ветками:</h1>

<p><b>На локальном сервере</b> <br>
	git checkout master <br>
	git pull origin master <br>
	git checkout -b {имя новой ветки} – все новые ветки создаем из master <br>
	вносим изменения в ветке <br>
	git add . <br>
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