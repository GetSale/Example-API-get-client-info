<?php
//Пример работы GetSale API для получения данных о пользователях

//Укажите свой ключ API
define('G_APIKEY', 'enter_yor_API_KEI');

//Получаем контент с внешних ресурсов
$requestBody = file_get_contents('php://input');

//Проверяем подлинность полученного запросa
//Логирование хэдеров
foreach (getallheaders() as $name => $value) {
    if ($name == "X-GetSale-Signature") {
        //Генерируем собственную подпись, используя API_KEY
        $signa = hash("sha256", "$requestBody" . G_APIKEY);
        //Сравниваем подписи
        if (strcmp($signa, $value) == 0) {
            //Пишем в log об успешном сравнении
            $file = 'log.txt';
            $current = file_get_contents($file);
            $current .= "Congrats! Signature verification is complete!\n";
            file_put_contents($file, $current);
        } else {
            //Пишем в log об ошибке
            $file = 'log.txt';
            $current = file_get_contents($file);
            $current .= "getSignature: " . $value . "\n";
            $current .= "yourSignature: " . $signa . "\n";
            $current .= "error: Signature verification failed. \n";
            file_put_contents($file, $current);
            //Заканчиваем работу скрипта, если ошибка
            exit;
        }
    }
}

//Продолжаем, если полученный запрос действительно от GetSale
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

//Фильтр на неинтересующие наc запросы, например, на конкретный виджет GetSale
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
