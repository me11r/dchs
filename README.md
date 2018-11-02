# emergency

## Развернуть
 
```bash
composer install
npm install
```
Заполнить ```.env``` файл нужными значениями.
```bash
artisan migrate
artisan db:seed
```

Это создаст пользователя ```admin@localhost``` с паролем ```password8``` и заполнит все справочники, кроме справочника улиц. Справочник улиц лежит в ```database/dumps/streets.sql``` залейте его удобным способом.

## Сборка
```bash
npm run prod
``` 

Это собирает в билд sass стили из ```resources/assets/scss/app.scss``` и Navbar компонент из ```resources/assets/js/ui/Navbar.vue```, копирует шрифты и статические файлы из папки ```resources/static/``` в публичный доступ.

Готово.

### Разработка
```bash
npm run hot
```
Для разаботки доступны обычные laravel-mix команды. 


### Настройка поисковой системы elasticsearch.
- Нужно установить на сервер elasticsearch 5.6.3 . 
Пример для ubuntu:
```bash
sudo apt-get install openjdk-8-jre
curl -L -O https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-5.6.3.deb
sudo dpkg -i elasticsearch-5.6.3.deb
sudo /etc/init.d/elasticsearch start
```

- Если elasticsearch находится не на том же сервере где и веб приложение, то в файл .env, находящийся в корне приложения - нужно вписать его адрес (по-умолчанию:
ELASTICSEARCH_HOST=http://localhost
)

- После установки и запуска elasticsearch необходимо заполнить индексы, после этого они будут автоматически обновляться. Список команд ниже, возможно, будет пополняться:
```bash
php artisan scout:import "App\Models\SpecialPlan"
php artisan scout:import "App\OperationalCard"
```
