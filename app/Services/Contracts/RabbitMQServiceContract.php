<?php

namespace App\Services\Contracts;

use PhpAmqpLib\Message\AMQPMessage;

interface RabbitMQServiceContract
{
    public function initConnection ();

    public function initChannel ();

    public function closeConnection ();

    public function closeChannel ();

    public function closeAll ();
    
    public function basicPublish (AMQPMessage $message, string $exchange = '', string $routingKey = '', bool $mandatory = false, bool $immediate = false, $ticket = null): void;

    public function sendRPCRequest (AMQPMessage $message, string $exchange = '', string $routingKey = '', bool $mandatory = false, bool $immediate = false, $ticket = null);

    public function receiveRPCResponse (AMQPMessage $response);

    public function waitForRPCResponse ();

    public function basicConsume (string $queue = '', string $consumerTag = '', bool $noLocal = false, bool $noAck = false, bool $exclusive = false, bool $nowait = false, $callback = null, int $ticket = null, array $arguments = array());

    public function declareQueue (string $queue = '', bool $passive = false, bool $durable = false, bool $exclusive = false, bool $autoDelete = true, bool $nowait = false, array $arguments = array(), ?int $ticket = null): void;

    public function declareAndReturnTemporaryQueue (string $queue = '', bool $passive = false, bool $durable = false, bool $exclusive = false, bool $autoDelete = true, bool $nowait = false, array $arguments = array(), ?int $ticket = null): string;
}
