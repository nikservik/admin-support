# Панель управления Службы Заботы

Используется вместе с пакетом `nikservik/admin-dashboard`.

## Установка

Добавить в `composer.json`
```bash
    "require": {
        ...
        "nikservik/admin-support": "^1.0",
        ...
    },
    "config": {
        ...
        "github-oauth": {
            "github.com": "токен доступа (создается в настройках)"
        }
    },
    "repositories" : [
        {
            "type": "vcs",
            "url" : "git@github.com:nikservik/admin-support.git"
        }
    ]
```
После этого выполнить 
```bash
composer update
```

## Конфигурация
Опубликовать конфигурацию:
```bash
php artisan vendor:publish --tag="admin-support-config"
```

В конфигурации можно изменить количество сообщений на странице:
```php
    // сколько сообщений загружается на страницу
    'messages-per-page' => 20,

```

## Использование

Добавляется как модуль в Панель управления.

## Тестирование

```bash
composer test
```

## Changelog


