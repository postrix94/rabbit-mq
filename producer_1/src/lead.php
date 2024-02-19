<?php
session_start();
require_once __DIR__ . "/rabbit_mq_producer.php";

__init();


function __init() {
    $info = sendMessageToConsumer(json_encode(getLead()));

    if($info['status'] === 'fail') {
        $_SESSION['error_connection_rabbitmq'] = ['message' => $info['message']];
    }

    $homePath = $_SERVER["HTTP_ORIGIN"];
    header("Location: {$homePath}");
    exit;
}

function getLead()
{
    $name = $_POST['name'] ?? "Default";
    $phone = $_POST['phone'] ?? "0000000";
    $comment = $_POST['comment'] ?? "Default comment";

    return [
        'name' => $name,
        'phone' => $phone,
        'comment' => $comment,
    ];
}



