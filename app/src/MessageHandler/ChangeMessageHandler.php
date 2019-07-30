<?php


namespace App\MessageHandler;

use App\Message\ChangeMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ChangeMessageHandler implements MessageHandlerInterface
{
    public function __invoke(ChangeMessage $message)
    {
        $connection = new AMQPStreamConnection('172.16.237.6', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('currenty_exchange', false, false, false, false);

        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, '', 'currenty_exchange');
    }
}