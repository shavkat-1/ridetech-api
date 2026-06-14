# RideTech API

RESTful API для сервиса райдшеринга, разработанный на Laravel.

## Описание

RideTech API позволяет пользователям регистрироваться в системе в роли пассажира или водителя, создавать и управлять поездками, добавлять транспортные средства, а также оставлять отзывы.

Примечание к версии V1: Проект реализован как MVP (Minimum Viable Product). В текущей реализации API сфокусировано на логике жизненного цикла поездки и строгой авторизации через Policy.


## Технологии

* PHP 8+
* Laravel 10
* PostgreSQL
* Doker(Sail)
* Laravel Sanctum
* Eloquent ORM
* Policies
* Service/Repository
* Feature Tests 


## Архитектура проекта

Проект следует принципам Layered Architecture (Controller-Service-Repository):

Http/Controllers: Обработка запросов и передача данных в сервисный слой.

Services: Бизнес-логика приложения.

Repositories: Абстракция над данными (Eloquent).



## Авторизация

Проект использует Laravel Policies для контроля доступа.

Примеры ограничений:

- Только пассажир может отменить свою поездку.
- Только водитель может принять поездку.
- Только водитель, принявший поездку, может её завершить.
- Только пассажир завершённой поездки может оставить отзыв.


## Возможности

### Аутентификация

* Регистрация пользователя
* Авторизация пользователя
* Выход из системы (отзыв токена)


## Жизненный цикл поездки

```text
PENDING
   ↓
IN_PROGRESS
   ↓
COMPLETED
```

или

```text
PENDING
   ↓
CANCELLED
```


#### Пассажир

* Создание поездки
* Просмотр списка своих поездок
* Просмотр деталей поездки
* Обновление поездки
* Отмена поездки

#### Водитель

* Просмотр доступных поездок
* Принятие поездки
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

## Структура проекта


```text
app
├── Http
│   ├── Controllers
│   ├── Requests
├── Models
├── Services
├── Repositories
├── Policies
└── Enums
```

## Установка проекта

### Клонирование репозитория

```bash
git clone https://github.com/shavkat-1/ridetech-api.git

cd ridetech-api
```

### Настройка окружения

```bash
cp .env.example .env
```

Настройте параметры подключения к базе данных при необходимости.

### Запуск контейнеров

```bash
./vendor/bin/sail composer install
---

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


## API будет доступно по адресу:


http://localhost


API Документация и тестирование
Для удобства взаимодействия с API в проекте предусмотрено два способа документации:

1. Swagger (OpenAPI)
Интерактивная документация, которая позволяет изучить структуру запросов и ответов прямо в браузере.

Ссылка: http://localhost/api/documentation

Примечание: Убедись, что твой сервер запущен, чтобы Swagger мог считать схему API.

2. Postman Collection
Если ты предпочитаешь тестировать запросы через Postman, используй готовую коллекцию.

Файл: ride-tech-collection.json (лежит в корне проекта).

Как использовать:

Нажми Import в Postman и выбери этот файл.

Настрой переменную baseUrl в коллекции (например, http://localhost).

Используй эндпоинт /api/v1/login для получения Bearer Token перед выполнением защищенных запросов.

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

### Trips for Passengers
| Method | Endpoint | Description  |
| ------ | -------- | -----------  |
| POST | /api/v1/trips             | Создать поездку |
| GET | /api/v1/trips              | Список поездок |
| GET | /api/v1/trips/{id}         | Детали поездки |
| PUT | /api/v1/trips/{id}         | Обновить поездку |
| POST | /api/v1/trips/{id}/cancel | Отменить поездку (только пассажир, статус PENDING)|

### Trips for Drivers
| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | /api/v1/driver/trips/available | Список доступных поездок |
| POST | /api/v1/trips/{id}/accept     | Принять поездку |
| POST | /api/v1/trips/{id}/complete   | Завершить поездку |


### Cars

| Method | Endpoint          | Description         |
| ------ | ----------------- | ------------------- |
| POST   | /api/v1/cars      | Добавить автомобиль |
| GET    | /api/v1/cars      | Список автомобилей  |
| PUT    | /api/v1/cars/{id} | Обновить автомобиль |
| DELETE | /api/v1/cars/{id} | Удалить автомобиль  |



### Reviews

| Method | Endpoint | Description |
|---------|----------|-------------|
| POST | /api/v1/trips/{trip}/reviews | Оставить отзыв по завершенной поездке |
| GET | /api/v1/trips/{trip}/reviews  | Получить отзывы по поездке |
| GET | /api/v1/reviews/{user}        | Получить отзывы водителя |


## Безопасность

* Laravel Sanctum
* Form Requests Validation
* Policies
* Защита от массового присвоения (Mass Assignment)
* Защита от SQL-инъекций через Eloquent ORM
* Rate Limiting

## Автор

Шавкат

Тестовое задание RideTech API, выполненное на Laravel.