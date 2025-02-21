<?php
require '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// Connect to RabbitMQ
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
$channel = $connection->channel();

$exchange_name = 'exchange_for_retrieve';
//$exchange_name = 'exchange_for_retrieve';
//$channel->exchange_declare($exchange_name, 'direct', false, false, false);

$queue_name1 = 'simple_queue_my1';
$channel->queue_declare($queue_name1, false, true, false, false, true);

// Declare a queue
$queue_name = 'simple_queue_my';
$channel->queue_declare($queue_name, false, true, false, false, true);

$routing_key = 'sms_dead_key';
$channel->queue_bind($queue_name, $exchange_name,$routing_key);
// Consume messages
$res = $channel->basic_get($queue_name,true);
if(is_object($res)){
    echo 'From '.$queue_name. '<br>';
    echo $res->body . '<br>';
}else{
    echo 'From '.$queue_name. '<br>';
    echo "No message";
}

$routing_key = 'sms_dead_key1';
$channel->queue_bind($queue_name1, $exchange_name,$routing_key);
// Consume messages
$res = $channel->basic_get($queue_name1,true);
if(is_object($res)){
    echo 'From '.$queue_name1. '<br>';
    echo $res->body . '<br>';
}else{
    echo 'From '.$queue_name1. '<br>';
    echo "No message";
}

// Close connection
$channel->close();
try {
    $connection->close();
} catch (\Exception $e) {
    throw new \Exception('Error connection close.');
}