<?php

// Connect to RabbitMQ
$connection = new \AMQPConnection([
    'host' => 'rabbitmq',
    'port' => 5672,
    'login' => 'user',
    'password' => 'password',
    'vhost' => '/'
]);
$connection->connect();
$channel = new \AMQPChannel($connection);

//Create if not exists exchanger
$exchange_name = 'exchange_for_retrieve_message';
$exchange = new \AMQPExchange($channel);
$exchange->setName($exchange_name);
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->setFlags(AMQP_DURABLE);
$exchange->declareExchange();


//Prepare queue for consumer
$queue_name_consumer = 'letter_crypto_for_ready_message';
$queue = new \AMQPQueue($channel);
$queue->setName($queue_name_consumer);
$queue->setFlags(AMQP_DURABLE);
$queue->declareQueue();

$routing_key = 'letters_crypto';
$queue->bind($exchange_name, $routing_key);

// Consume messages
$queueList = [];
while ($message = $queue->get()) {
    echo "<pre>";
    print_r($message);
    //$queue->ack($message->getDeliveryTag());
    die();
    $queueList[]= $message->getBody();

}

if(!empty($queueList)){
    echo "<pre>";
    print_r($queueList);
}else{
    echo 'No messages';
}


$connection->disconnect();