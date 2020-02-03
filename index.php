<?php

use MeterDataBot\Commands\CommandFactory;


require __DIR__ . '/vendor/autoload.php';




$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);

$command = CommandFactory::fromPayload($payload);
$command->execute($payload);
