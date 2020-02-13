<?php declare(strict_types=1);

namespace MeterDataBot;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiRequest
{
    /**
     * @var string
     */
    private $apiUri;

    /**
     * @var string
     */
    private $botSecret;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(string $apiUri, string $botSecret, HttpClientInterface $httpClient)
    {
        $this->apiUri = $apiUri;
        $this->botSecret = $botSecret;
        $this->httpClient = $httpClient;
    }

    /**
     * @param int    $chatId
     * @param string $text
     *
     * @throws TransportExceptionInterface
     */
    public function sendMessage(int $chatId, string $text): void
    {
        $this->httpClient->request(
            'POST',
            $this->buildUri('/sendMessage'),
            [
                'body' => [
                    'chat_id' => $chatId,
                    'text' => $text,
                ],
            ]
        );
    }

    private function buildUri(string $method): string
    {
        return $this->apiUri . $this->botSecret . $method;
    }
}