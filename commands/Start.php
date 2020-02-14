<?php declare(strict_types=1);

namespace MeterDataBot\Commands;

use MeterDataBot\ApiRequest;
use MeterDataBot\Commands\System\CommandInterface;

/**
 * Example of custom command handler
 *
 * @handles /start
 */
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
        $this->apiRequest->sendMessage(
            (int) $payload['message']['chat']['id'],
            'Hello. I\'m a bot :)'
        );
    }
}