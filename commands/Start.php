<?php

namespace MeterDataBot\Commands;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Start implements CommandInterface
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
//
//    /**
//     * Start constructor.
//     *
//     * @param HttpClientInterface $httpClient
//     */
//    public function __construct(HttpClientInterface $httpClient)
//    {
//        $this->httpClient = $httpClient;
//    }

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
                    'text' => $messages['hello'],
                ],
            ]
        );
    }
}