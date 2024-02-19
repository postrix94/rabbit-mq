<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$config = [
    'host' => '',
    'virtual_host' => '',
    'port' => 5672,
    'user' => '',
    'password' => '',
    'queries' => [
        'b2c_admin' => [
            'name' => 'b2c_admin',
            'no_ack' => false,
            'nowait' => false,
            'prefetch_count' => 1,
        ]
    ],
];


try {
    $connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password'], $config['virtual_host']);
    $channel = $connection->channel();
    //Второй параметр сколько сообщений обрабатывать из очереди за раз
    $channel->basic_qos(0, $config['queries']['b2c_admin']['prefetch_count'], false);


    echo " [*] Waiting for messages. To exit press CTRL+C\n";


    $callback = function ($msg) use ($channel) {
        echo ' [x] Received ', $msg->body, "\n";

        // Явное подтверждение доставки и удаление из очереди
        $channel->basic_ack($msg->delivery_info['delivery_tag']);

    };

//  auto_ack (также известным как no_ack в некоторых библиотеках).
// Когда auto_ack установлен в true, брокер автоматически подтверждает (удаляет) сообщение из очереди, как только оно отправлено потребителю.
// Без подтверждения о доставке!!!
    $channel->basic_consume($config['queries']['b2c_admin']['name'], '', false, $config['queries']['b2c_admin']['no_ack'], false, $config['queries']['b2c_admin']['nowait'], $callback);


    $channel->consume();
} catch (\Exception $exception) {
    echo $exception->getMessage();
}