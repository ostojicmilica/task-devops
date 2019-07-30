<?php


namespace App\Message;
use PhpAmqpLib\Connection\AMQPConnection;

class ChangeMessage
{
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }
}