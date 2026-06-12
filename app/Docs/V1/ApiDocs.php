<?php

namespace App\Docs\V1;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="RideTech API Documentation",
 * description="Чистая интерактивная документация API для сервиса RideTech (вынесена отдельно от контроллеров)",
 * @OA\Contact(
 * email="shavkat@example.com"
 * )
 * )
 *
 * @OA\Server(
 * url="/api/v1",
 * description="Основной сервер разработки"
 * )
 * * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * type="http",
 * scheme="bearer",
 * bearerFormat="JWT",
 * description="Введите ваш API токен в формате: Bearer {token}"
 * )
 */
class ApiDocs
{
}