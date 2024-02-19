<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

function sendMessageToConsumer(string $message)
{
    try {
        $connection = new AMQPStreamConnection('', 5672, '', '', '');
        $channel = $connection->channel();

        // с параметром nowait установленным в true. Таким образом, после объявления очереди/обменника, код не будет ожидать ответа от сервера и сразу перейдет к следующим инструкциям.
        // с параметром durable установленным в true. Очередь не будет удалятся при перезагрузке сервера.
        $channel->queue_declare('b2c_lead', false, true, false, false, false);
        $channel->queue_declare('b2c_admin', false, true, false, false, false);

        $channel->exchange_declare('steko_ua', 'direct', false, true, false);

        $channel->queue_bind('b2c_lead', 'steko_ua', 'lead');
        $channel->queue_bind('b2c_admin', 'steko_ua', 'admin');



        //  Сохраняет сообщения при перезагрузке сервера delivery_mode === 2
        $msg = new AMQPMessage($message,['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $channel->basic_publish($msg, 'steko_ua', 'lead');
        $channel->basic_publish($msg, 'steko_ua', 'admin');


        $channel->close();
        $connection->close();
    } catch (Exception $e) {
        return ['status' => 'fail', 'message' => $e->getMessage()];
    }

    return ['status' => 'success','message' => 'Connection success'];
}

