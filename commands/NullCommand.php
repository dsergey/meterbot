<?php

namespace MeterDataBot\Commands;

/**
 * Null command execute
 */
class NullCommand implements CommandInterface
{
    public function execute(array $payload): void
    {

    }

}