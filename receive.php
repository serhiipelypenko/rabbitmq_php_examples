<?php
require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// Connect to RabbitMQ
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
$channel = $connection->channel();

// Declare a queue
$channel->queue_declare('task_queue', false, true, false, false);

echo " [*] Waiting for messages. To exit, press CTRL+C\n";


// Callback function to process messages
$callback = function ($msg) {
    echo " [x] Received ", $msg->body, "\n";
};

// Consume messages
$channel->basic_consume('task_queue', '', false, true, false, false, $callback);

// Keep script running to listen for messages
while ($channel->is_consuming()) {
    $channel->wait();
}

// Close connection
$channel->close();
$connection->close();
?>