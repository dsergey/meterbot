<?php

namespace MeterDataBot\Commands;

use Symfony\Component\HttpClient\HttpClient;

class Add implements CommandInterface
{
    public function execute(array $payload): void
    {
        $h = HttpClient::create();
        $h->request(
            'POST',
            $_ENV['API_URI'] . $_ENV['BOT_SECRET'] . '/sendMessage',
            [
                'body' => [
                    'chat_id' => $payload['message']['chat']['id'],
                    'text' => 'Adding😝',
                ],
            ]
        );
    }

}