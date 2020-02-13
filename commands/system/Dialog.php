<?php declare(strict_types=1);

namespace MeterDataBot\Commands\System;

use MeterDataBot\ApiRequest;

/**
 * Executes when user sent not a command (some message)
 * it can be answers for some quiz
 */
class Dialog implements CommandInterface
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
        // TODO: Implement execute() method.
    }

}