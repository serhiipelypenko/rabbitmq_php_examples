<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
require '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;



// Connect to RabbitMQ
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
$channel = $connection->channel();

$exchange_name = 'exchange_for_retrieve';
$channel->exchange_declare($exchange_name, 'direct', false, false, false);

$exchange_name_dead = 'exchange_for_dead_message';
$channel->exchange_declare($exchange_name_dead, 'fanout', false, false, false);

$queue_name = 'simple_queue';
$params = new AMQPTable(array(
    'x-dead-letter-exchange' => 'exchange_for_retrieve'
    //'x-dead-letter-routing-key' => 'simple_queue'
));
$channel->queue_declare($queue_name, false, true, false, false,false,$params);

$routing_key = 'sms_dead_key1';
$channel->queue_bind($queue_name, $exchange_name_dead,$routing_key);

// Message content
$data = "Hello, RabbitMQ - for simple key - exchange direct";
$msg = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,'expiration' => 10000]);
//$msg = new AMQPMessage($data, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT, 'expiration' => 500]);
// Publish message
$channel->basic_publish($msg, $exchange_name_dead, $routing_key);
//$channel->basic_publish($msg, $exchange_name, $routing_key);

echo " [x] Sent '$data'\n";

// Close connection
$channel->close();
$connection->close();

?>