<?php

namespace MeterDataBot\Commands;

/**
 * Null command execute
 */
class NullCommand implements CommandInterface
{
    public function execute(array $payload): void
    {
        $messages = require (__DIR__ . '/../messages/ru.php');

        echo $messages['wrongCommand'];
    }

}