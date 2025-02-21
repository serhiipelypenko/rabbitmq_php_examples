<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

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

$exchange_name = 'exchange_for_retrieve_message';
$exchange = new \AMQPExchange($channel);
$exchange->setName($exchange_name);
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->setFlags(AMQP_DURABLE);
$exchange->declareExchange();


$exchange_name_dead = 'exchange_for_dead_message';
$exchange = new \AMQPExchange($channel);
$exchange->setName($exchange_name_dead);
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->setFlags(AMQP_DURABLE);
$exchange->declareExchange();

$queue_name = 'letters_crypto_for_dead_message';
$queue = new \AMQPQueue($channel);
$queue->setName($queue_name);
$queue->setFlags(AMQP_DURABLE);
$queue->setArguments(
    array(
    'x-dead-letter-exchange' => $exchange_name
    //'x-dead-letter-routing-key' => 'simple_queue'
    ));
$queue->declareQueue();

$routing_key = 'letters_crypto';
$queue->bind($exchange_name_dead, $routing_key);


$data = "Letter for crypto";
$exchange->publish($data, $routing_key,null, ['delivery_mode' => \AMQP_DELIVERY_MODE_PERSISTENT,'expiration' => 10000]);

//Prepare queue for consumer
$queue_name_consumer = 'letter_crypto_for_ready_message';
$queue = new \AMQPQueue($channel);
$queue->setName($queue_name_consumer);
$queue->setFlags(AMQP_DURABLE);
$queue->declareQueue();

$routing_key = 'letters_crypto';
$queue->bind($exchange_name, $routing_key);



echo " [x] Sent '$data'\n";

// Close connection
$channel->close();
$connection->disconnect();

?>