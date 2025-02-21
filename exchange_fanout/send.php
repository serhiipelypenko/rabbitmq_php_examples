<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
require '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

// Connect to RabbitMQ
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
$channel = $connection->channel();

$exchange_name = 'simple_exchange_fanout';
$channel->exchange_declare($exchange_name, 'fanout', false, false, false);

$queue_name = 'simple_queue_fanout';
$channel->queue_declare($queue_name, false, true, false, false);

$queue_name_example = 'simple_queue2_fanout';
$channel->queue_declare($queue_name_example, false, true, false, false);

$routing_key = 'simple_key';
//$channel->queue_bind($queue_name, $exchange_name,$routing_key);

// Message content
$data = "Hello, RabbitMQ - for simple key - exchange fanout";
$msg = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

// Publish message
$channel->basic_publish($msg, $exchange_name, $routing_key);
//$channel->basic_publish($msg, $exchange_name, $routing_key);



echo " [x] Sent '$data'\n";

// Close connection
$channel->close();
$connection->close();
?>