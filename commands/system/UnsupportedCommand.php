<?php declare(strict_types=1);

namespace MeterDataBot\Commands\System;

use MeterDataBot\ApiRequest;

/**
 * Executes when user sent not supported command
 */
class UnsupportedCommand implements CommandInterface
{
    private $apiRequest;

    public function __construct(ApiRequest $apiRequest)
    {
        $this->apiRequest = $apiRequest;
    }

    public function execute(array $payload): void
    {
        $this->apiRequest->sendMessage(
            (int) $payload['message']['chat']['id'],
            'The command is not supported'
        );
    }

}