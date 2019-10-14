<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Extensions\AmqpConnectionChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class DirectMqProducer extends Command
{
    use AmqpConnectionChannel;

    protected $signature = 'direct:publisher {message}';
    protected $description = 'RabbitMQ direct producer';

    public function handle()
    {
        try {
            $text = trim($this->argument('message'));
            $ex = 'direct.exchange';
            $r = 'direct.routing.key';
            $message = new AMQPMessage($text);
            /* @var AMQPStreamConnection $connection */
            /* @var \PhpAmqpLib\Channel\AMQPChannel $channel */
            [$connection, $channel] = $this->setup();
            $channel->queue_declare('exchange-queue', false, false, false, false);
            $channel->basic_publish($message, '', 'hello2');
            $channel->close();
            $connection->close();
            $this->info(sprintf("exchange rates reported to other services successfully, [%s]", $text));
        } catch (\Exception $e) {
            $this->error(sprintf("error occurred on getting exchange rates: [%s]", $e->getMessage()));

        }
    }
}