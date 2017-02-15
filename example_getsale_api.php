<?php
//Пример работы GetSale API для получения данных о пользователях

//Укажите свой ключ API
define('G_APIKEY', 'your_API_KEY');

//Получаем контент от GetSale
$requestBody = file_get_contents('php://input');

//Проверяем подлинность полученного запросa
//Логирование хэдеров
foreach (getallheaders() as $name => $value) {
    if ($name == "X-GetSale-Signature") {
        $newSigna = hash("sha256", "$requestBody" . G_APIKEY);

        if (strcmp($newSigna, $value) == 0) {
            //Пишем в log
            $file = 'log.txt';
            $current = file_get_contents($file);
            $current .= "getSigna: " . $value . "\n";
            $current .= "mySigna: " . $newSigna . "\n";
            file_put_contents($file, $current);
        } else {
            //Пишем в log
            $file = 'log.txt';
            $current = file_get_contents($file);
            $current .= "getSigna: " . $value . "\n";
            $current .= "mySigna: " . $newSigna . "\n";
            $current .= "error: Signature verification failed. \n";
            file_put_contents($file, $current);
            exit;
        }
    }
}

//Продолжаем, если запрос полученный от GetSale правильный
$decodedBody = json_decode($requestBody, true);
$fields = $decodedBody['fields'];

$eventEmail = $fields['email'];
$eventPhone = $fields['phone'];
$eventFirstName = $fields['firstName'];
$eventLastName = $fields['lastName'];
$eventOther = $fields['other'];
$widget_id = $decodedBody['widget_id'];
$eventUrl = $decodedBody['url'];

//Сообщаем об успешном получении, сообщаем 200OK
http_response_code(200);

//Фильтр на неинтересующие наc запросы
if (empty($widget_id)) {
    exit;
}

//Логирование полученных данных от GetSale
$file = 'log.txt';
$current = file_get_contents($file);
$current .= 'time: ' . date('Y-m-d h:i:s A') . '\n';
$current .= "fields: eventEmail:$eventEmail,  eventPhone:$eventPhone, eventFirstName: $eventFirstName, eventLastName: $eventLastName, eventOther: $eventOther, eventUrl: $eventUrl, widget_id: $widget_id \n";
file_put_contents($file, $current);
?>
