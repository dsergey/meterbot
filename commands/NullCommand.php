<?php

namespace MeterDataBot\Commands;

use Symfony\Component\HttpClient\HttpClient;

/**
 * Null command execute
 */
class NullCommand implements CommandInterface
{
    public function execute(array $payload): void
    {
        $messages = require(__DIR__ . '/../messages/ru.php');

        $h = HttpClient::create();
        $h->request(
            'POST',
            $_ENV['API_URI'] . $_ENV['BOT_SECRET'] . '/sendMessage',
            [
                'body' => [
                    'chat_id' => $payload['message']['chat']['id'],
                    'text' => $messages['wrongCommand'],
                ],
            ]
        );
    }

}