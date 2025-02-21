<?php
require '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// Connect to RabbitMQ
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
$channel = $connection->channel();

//$exchange_name = 'simple_exchange';
//$channel->exchange_declare($exchange_name, 'direct', false, false, false);

// Declare a queue
$queue_name = 'simple_queue_fanout';
$channel->queue_declare($queue_name, false, true, false, false, true);

// Declare a queue
$queue_name_example = 'simple_queue2_fanout';
$channel->queue_declare($queue_name_example, false, true, false, false, true);

// Consume messages
$res = $channel->basic_get($queue_name,true);
if(is_object($res)){
    echo 'From '.$queue_name. '<br>';
    echo $res->body . '<br>';
}else{
    echo 'From '.$queue_name. '<br>';
    echo "No message" . '<br>';
}

// Consume messages
$res = $channel->basic_get($queue_name_example,true);
if(is_object($res)){
    echo 'From '.$queue_name_example. '<br>';
    echo $res->body . '<br>';
}else{
    echo 'From '.$queue_name_example. '<br>';
    echo "No message" . '<br>';
}

// Close connection
$channel->close();
try {
    $connection->close();
} catch (\Exception $e) {
    throw new \Exception('Error connection close.');
}