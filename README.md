# RideTech API

RESTful API для сервиса райдшеринга, разработанный на Laravel.

## Описание

RideTech API позволяет пользователям регистрироваться в системе в роли пассажира или водителя, создавать и управлять поездками, добавлять транспортные средства, а также оставлять отзывы о водителях.

Проект разработан в соответствии с принципами SOLID, MVC и best practices Laravel.

## Технологии

* PHP 8+
* Laravel 10
* MySQL / PostgreSQL
* Laravel Sanctum
* Eloquent ORM
* Swagger / Postman
* PHPUnit

## Возможности

### Аутентификация

* Регистрация пользователя
* Авторизация пользователя
* Выход из системы (отзыв токена)

### Управление поездками

#### Пассажир

* Создание поездки
* Просмотр истории поездок
* Просмотр деталей поездки
* Обновление поездки
* Отмена поездки

#### Водитель

* Просмотр доступных поездок
* Принятие поездки
* Отклонение поездки
* Завершение поездки

### Управление автомобилями

* Добавление автомобиля
* Просмотр списка автомобилей
* Обновление автомобиля
* Удаление автомобиля

### Отзывы и рейтинг

* Оставление отзыва водителю
* Просмотр отзывов водителя

### Дополнительно

* Пагинация
* Фильтрация поездок
* Feature-тесты

## Архитектура проекта

```text
app
├── Http
│   ├── Controllers
│   ├── Requests
│   └── Resources
├── Models
├── Services
├── Repositories
├── Policies
├── Filters
└── Enums
```

## Установка проекта

### Клонирование репозитория

```bash
git clone https://github.com/your-username/ridetech-api.git

cd ridetech-api
```

### Настройка окружения

```bash
cp .env.example .env
```

Настройте параметры подключения к базе данных при необходимости.

### Запуск контейнеров

```bash
./vendor/bin/sail up -d
```

### Установка зависимостей

```bash
./vendor/bin/sail composer install
```

### Генерация ключа приложения

```bash
./vendor/bin/sail artisan key:generate
```

### Выполнение миграций

```bash
./vendor/bin/sail artisan migrate
```


### Проверка работы приложения

API будет доступно по адресу:

```text
http://localhost
```

Swagger документация:

```text
http://localhost/api/documentation
```

### Запуск тестов

```bash
./vendor/bin/sail artisan test
```

## Аутентификация

Для доступа к защищенным маршрутам используется Laravel Sanctum.

После успешного входа необходимо передавать токен в заголовке:

```http
Authorization: Bearer YOUR_TOKEN
```

## API Endpoints

### Authentication

| Method | Endpoint         | Description |
| ------ | ---------------- | ----------- |
| POST   | /api/v1/register | Регистрация |
| POST   | /api/v1/login    | Авторизация |
| POST   | /api/v1/logout   | Выход       |

### Trips

| Method | Endpoint           | Description      |
| ------ | ------------------ | ---------------- |
| POST   | /api/v1/trips      | Создать поездку  |
| GET    | /api/v1/trips      | Список поездок   |
| GET    | /api/v1/trips/{id} | Детали поездки   |
| PUT    | /api/v1/trips/{id} | Обновить поездку |
| DELETE | /api/v1/trips/{id} | Отменить поездку |

### Cars

| Method | Endpoint          | Description         |
| ------ | ----------------- | ------------------- |
| POST   | /api/v1/cars      | Добавить автомобиль |
| GET    | /api/v1/cars      | Список автомобилей  |
| PUT    | /api/v1/cars/{id} | Обновить автомобиль |
| DELETE | /api/v1/cars/{id} | Удалить автомобиль  |

### Reviews

| Method | Endpoint                    | Description     |
| ------ | --------------------------- | --------------- |
| POST   | /api/v1/reviews/{driver_id} | Оставить отзыв  |
| GET    | /api/v1/reviews/{driver_id} | Получить отзывы |

## Тестирование

Запуск тестов:

```bash
php artisan test
```

или

```bash
vendor/bin/phpunit
```

## Документация API

Swagger:

```text
http://127.0.0.1:8000/api/documentation
```

или

Postman Collection:

```text
docs/RideTech.postman_collection.json
```

## Безопасность

* Laravel Sanctum
* Form Requests Validation
* Policies
* Защита от массового присвоения (Mass Assignment)
* Защита от SQL-инъекций через Eloquent ORM
* Rate Limiting

## Автор

Тестовое задание RideTech API.
