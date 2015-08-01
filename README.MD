Алгоритм работы с тестовым и рабочим сайтами и ветками:

На локальном сервере
git checkout master
git pull origin master
git checkout -b {имя новой ветки} – все новые ветки создаем из master
вносим изменения в ветке
git add .
git commit -m "коммент к изменению в ветке"
git checkout dev
git pull origin dev
git merge {имя новой ветки}
git push origin dev

На удаленном сервере:
cd /home/bitrix/ext_www/dev.luxurytravelmart.ru/ - переходим на тестовый сайт
git checkout dev (по идее мы уже итак должны быть в ветке dev в данной папке, но на всякий случай)
git pull origin dev
После этого проводятся тесты на dev.luxurytravelmart.ru. Если все ок, тогда:

На локальном сервере:
git checkout master
git pull origin master
git merge {имя новой ветки}
git push origin master

На удаленном сервере:
cd /home/bitrix/www/
git checkout master - опять же, мы на рабочем сайте ВСЕГДА должны быть на ветке master
git pull origin master

С уважением,
Дмитрий Каплунов.