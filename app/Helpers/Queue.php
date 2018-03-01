<?php

namespace App\Helpers;

class Queue
{
    private $exchanges = [];
    /**
     * @var \AMQPChannel
     */
    private $channel;

    public function __construct(\AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    public function publish($queue, $exchange, $data, $durable = true)
    {
        if (!is_string($data)) {
            $data = json_encode($data);
        }

        $flag = $durable ? AMQP_DURABLE : AMQP_NOPARAM;
        $route = $exchange . '_' . $queue;
        $ex = $this->getExchange($queue, $exchange, $durable);

        $ex->publish($data, $route, $flag, ['delivery_mode' => 2]);
        return true;
    }

    private function getExchange($queue, $exchange, $durable)
    {

        $hash = md5($queue . $exchange);

        if (isset($this->exchanges[$hash])) {
            return $this->exchanges[$hash];
        }
        $flag = $durable ? AMQP_DURABLE : AMQP_NOPARAM;
        $route = $exchange . '_' . $queue;
        $channel = $this->getChannel();
        $ex = $this->declareExchange($channel, $exchange, $flag);
        $this->declareQueue($channel, $queue, $exchange, $route, $flag);
        $this->exchanges[$hash] = $ex;

        return $ex;
    }

    public function consume($queue, $exchange, $callback, $durable = true, $autoDelete = false, $passive = false)
    {
        sleep(1);

        $flag = $durable ? AMQP_DURABLE : AMQP_NOPARAM;
        $route = $exchange . '_' . $queue;
        $channel = $this->getChannel();
        $q = $this->declareQueue($channel, $queue, $exchange, $route, $flag);
        $q->consume(function ($msg, $q) use ($callback) {
            $msgBody = json_decode($msg->getBody(), true);
            $res = $callback($msgBody, $q, $msg);
            if ($res !== false) {
                $this->ack($msg, $q);
            }
        }, $flag, null);
    }

    private function declareExchange(\AMQPChannel $channel, $exchange, $flag)
    {
        $ex = new \AMQPExchange($channel);
        $ex->setName($exchange);
        $ex->setType(AMQP_EX_TYPE_DIRECT);
        $ex->setFlags($flag);
        $ex->declareExchange();

        return $ex;
    }

    private function getChannel(): \AMQPChannel
    {
        return $this->channel;
    }

    private function declareQueue(\AMQPChannel $channel, $queue, $exchange, $route, $flag)
    {
        $q = new \AMQPQueue($channel);
        $q->setName($queue);
        $q->setFlags($flag);
        $q->declareQueue();
        $q->bind($exchange, $route);

        return $q;
    }

    public function async($diName = false, $function, $params, $callerClass)
    {
        return $this->publish('async', 'async', [
            'function' => $function,
            'diname' => $diName,
            'params' => $params,
            'caller' => $callerClass,
        ]);
    }

    /**
     * @param $msg \AMQPEnvelope
     * @param $queue \AMQPQueue
     * @return bool
     */
    public function ack($msg, $queue)
    {
        return $queue->ack($msg->getDeliveryTag(), $queue->getFlags());
    }

    /**
     * @param $msg \AMQPEnvelope
     * @param $queue \AMQPQueue
     * @return bool
     */
    public function nack($msg, $queue)
    {
        return $queue->nack($msg->getDeliveryTag(), AMQP_REQUEUE);
    }

    /**
     * @param $msg \AMQPEnvelope
     * @param $queue \AMQPQueue
     * @return bool
     */
    public function reject($msg, $queue)
    {
        return $queue->reject($msg->getDeliveryTag(), AMQP_REQUEUE);
    }
}