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

    /**
     * Start constructor.
     *
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function execute(array $payload): void
    {
        $this->httpClient->request();
    }
}