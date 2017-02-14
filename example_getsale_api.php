<?php
//Пример работы GetSale API для получения данных о пользователях

//Получение контента от GetSale
$requestBody = file_get_contents('php://input');

$decodedBody = json_decode($requestBody, true);
$fields = $decodedBody['fields'];

//Получаем данные
$eventEmail = $fields['email'];
$eventFirstName = $fields['firstName'];
$widget_id = $decodedBody['widget_id'];

//TO_DO Добавить поле url

//Сообщаем об успешном получении, отвечаем 200OK
http_response_code(200);

//Фильтр на неинтересующие нас запросы
if (empty($widget_id)) {
    exit;
}

//Продолжаем, если запрос от GetSale получен
//TO_DO Проверяем подлинность полученного запроса

//Используем полученые данные от GetSale
//Например, запишим их в log
$file = 'log/log.txt';
$current = file_get_contents($file);
$current .= 'time: ' . date('Y-m-d h:i:s A') . '\n';
$current .= "fields: eventEmail: $eventEmail, eventFirstName: $eventFirstName, widget_id: $widget_id. \n";
file_put_contents($file, $current);
?>
