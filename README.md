# Rabbitmq php examples

1. Create Dockerfile. It sets up a PHP 8 environment with required extensions and installs Composer.
2. Create docker-compose.yml. This file defines the PHP Apache container and the RabbitMQ service.
3. Install Dependencies. Run the following commant to install the PHP AMQP library
```
docker compose run --rm php composer require paragonie/constant_time_encoding  paragonie/random_compat php-amqplib/php-amqplib
```
4. Create send.php (Producer). This script sends a message to RabbitMQ.
5. Create receive.php (Consumer). This script receives message from RabbitMQ. (Also I created receive_not_stream.php - for run scripts not like a demon)
6. Build and Run containers
```
docker compose up -d --build
```
7. Send and Receive Messages

Send a message
Run the producer script inside the container
```
docker compose exec php php send.php
```
You should see output like
```
[x] Sent 'Hello, RabbitMQ!'
```
Receive messages
Run the consumer script inside the container:
```
docker compose exec php php receive.php
```
You should see output like
```
[*] Waiting for messages. To exit, press CTRL+C
[x] Received Hello, RabbitMQ!
```

8. Access RabbitMQ Management UI
You can access the RabbitMQ dashboard at:
```
http://localhost:15672
```
Login with:
- Username: user
- Password: password

9. Also - in different folders I create producers and consumers with different options:
 - dead_message and dead_message_amq_connect - show how can send message and retrieve their with some delay
 - exchange_direct and exchange_fanout - with different exchanges