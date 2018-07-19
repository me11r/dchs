#emergency

##Развернуть
 
```bash
composer install
npm install
```
Заполнить ```.env``` файл нужными значениями.
```bash
artisan migrate
artisan db:seed
```

Это создаст пользователя ```admin@localhost``` с паролем ```password``` и заполнит все справочники, кроме справочника улиц. Справочник улиц лежит в ```database/dumps/streets.sql``` залейте его удобным способом.

## Сборка
```npx build``` или ```npm run build``` в зависимости от установленной версии ноды.
Это собирает в билд sass стили из ```resources/assets/scss/app.scss``` и Navbar компонент из ```resources/assets/js/ui/Navbar.vue```, копирует шрифты и статические файлы из папки ```resources/static/``` в публичный доступ.
Для версионирования таких файлов создается ```manifest.json``` доступный через Twig хелпер примерно так: ```{{ manifest('assets/js/app.js') }}```.
 Остальные компоненты пока лежат в шаблонах, и их нужно оттуда выносить. 

Готово.