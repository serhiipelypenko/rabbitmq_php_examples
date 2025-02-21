<?php
require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// Connect to RabbitMQ
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
$channel = $connection->channel();

// Declare a queue
$channel->queue_declare('task_queue', false, true, false, false, true);

// Consume messages
//$channel->basic_consume('task_queue', '', false, true, false, false, $callback);
$res = $channel->basic_get('task_queue',true);
if(is_object($res)){
    echo $res->body . '<br>';
}else{
    echo "No message";
}

// Close connection
$channel->close();
try {
    $connection->close();
} catch (\Exception $e) {
    throw new \Exception('Error connection close.');
}