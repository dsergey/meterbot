<?php declare(strict_types=1);

namespace MeterDataBot\Commands\System;

use MeterDataBot\ApiRequest;

/**
 * Executes when user sent not supported command
 */
class UnsupportedCommand implements CommandInterface
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
        $messages = require(ROOT_DIR . '/messages/ru.php');

        $this->apiRequest->sendMessage(
            (int) $payload['message']['chat']['id'],
            $messages['wrongCommand']
        );
    }

}