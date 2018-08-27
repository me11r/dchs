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
