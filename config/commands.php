<?php

use MeterDataBot\Commands as Command;

/**
 * Here is the list of supported commands
 *
 * You can add several command handlers for one command,
 * they will be executed in order they are defined
 * each command must implement CommandInterface
 */

return [
    '/start' => [Command\Start::class],
];