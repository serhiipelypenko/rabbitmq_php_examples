<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Connect to RabbitMQ
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
$channel = $connection->channel();

// Declare a queue
$channel->queue_declare('task_queue1', false, true, false, false);

// Message content
$data = "Hello, RabbitMQ!!!!";
$msg = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

// Publish message
$channel->basic_publish($msg, '', 'task_queue');

echo " [x] Sent '$data'\n";

// Close connection
$channel->close();
$connection->close();
?>