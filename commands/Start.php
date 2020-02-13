<?php declare(strict_types=1);

namespace MeterDataBot\Commands;

use MeterDataBot\ApiRequest;
use MeterDataBot\Commands\System\CommandInterface;

class Start implements CommandInterface
{
    /**
     * @var ApiRequest
     */
    private $apiRequest;

    public function __construct(ApiRequest $apiRequest)
    {
        $this->apiRequest = $apiRequest;
    }

    public function execute(array $payload): void
    {
        $messages = require(__DIR__ . '/../messages/ru.php');

        $this->apiRequest->sendMessage(
            (int) $payload['message']['chat']['id'],
            $messages['hello']
        );
    }
}