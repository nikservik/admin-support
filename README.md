# :package_description

Используется вместе с пакетом `nikservik/admin-dashboard`.

## Установка

```bash
composer require vendor_slug/package_slug
```

## Использование

Для подключения к Админ-панели нужно добавить название и описание в файл переводов:
```php
// файл resources/lang/ru/admin.php

return [
    // название и описание, которые будут отображаться в admin-dashboard
    'dashboardName' => 'Название',
    'dashboardDescription' => 'Небольшое описание',
    ...
```

## Тестирование

```bash
composer test
```

## Changelog


