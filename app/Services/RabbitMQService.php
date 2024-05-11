<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService implements Contracts\RabbitMQServiceContract
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    private ?string $rpcCorelationId = null;
    private ?string $rpcResponse = null;

    /**
     * @param string|null $rpcCorelationId
     */
    public function setRpcCorelationId (?string $rpcCorelationId) : void
    {
        $this->rpcCorelationId = $rpcCorelationId;
    }

    /**
     * @return string|null
     */
    public function getRpcCorelationId () : ?string
    {
        return $this->rpcCorelationId;
    }

    /**
     * @param string|null $rpcResponse
     */
    public function setRpcResponse (?string $rpcResponse) : void
    {
        $this->rpcResponse = $rpcResponse;
    }

    /**
     * @return string|null
     */
    public function getRpcResponse () : ?string
    {
        return $this->rpcResponse;
    }

    public function __construct () {}


    /**
     * @throws Exception
     */
    public function basicPublish (AMQPMessage $message, string $exchange = '', string $routingKey = '', bool $mandatory = false, bool $immediate = false, $ticket = null) : void
    {
        $this->channel->basic_publish(...func_get_args());
    }

    public function basicConsume (string $queue = '', string $consumerTag = '', bool $noLocal = false, bool $noAck = false, bool $exclusive = false, bool $nowait = false, $callback = null, int $ticket = null, array $arguments = [])
    {
        $this->channel->basic_consume(...func_get_args());
    }

    public function declareQueue (string $queue = '', bool $passive = false, bool $durable = false, bool $exclusive = false, bool $autoDelete = true, bool $nowait = false, array $arguments = [], ?int $ticket = null) : void
    {
        $this->channel->queue_declare(...func_get_args());
    }

    public function declareAndReturnTemporaryQueue (string $queue = '', bool $passive = false, bool $durable = false, bool $exclusive = false, bool $autoDelete = true, bool $nowait = false, array $arguments = [], ?int $ticket = null) : string
    {
        [$queue, ,] = $this->channel->queue_declare(...func_get_args());
        return $queue;
    }

    /**
     * @throws Exception
     */
    public function initConnection ()
    {
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
    }

    public function initChannel ()
    {

        $this->channel = $this->connection->channel();

    }

    /**
     * @throws Exception
     */
    public function closeConnection ()
    {
        $this->connection->close();
    }

    public function closeChannel ()
    {
        $this->channel->close();
    }

    /**
     * @throws Exception
     */
    public function closeAll ()
    {
        $this->closeChannel();
        $this->closeConnection();
    }

    /**
     * @throws Exception
     */
    public function sendRPCRequest (AMQPMessage $message, string $exchange = '', string $routingKey = '', bool $mandatory = false, bool $immediate = false, $ticket = null)
    {
        $this->rpcCorelationId = Str::orderedUuid();
        $message->set('correlation_id', $this->rpcCorelationId);
        $this->basicPublish(...func_get_args());
        $this->rpcResponse = null;
    }

    public function receiveRPCResponse (AMQPMessage $response)
    {
        if ($response->get('correlation_id') == $this->rpcCorelationId)
        {
            $this->rpcResponse = $response->body;
        }

        $this->rpcCorelationId = null;
    }

    public function waitForRPCResponse ()
    {
        while ($this->rpcResponse == null)
        {
            $this->channel->wait(null, false, 5);
        }
    }
}
