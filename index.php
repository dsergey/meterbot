<?php

use MeterDataBot\Commands\CommandFactory;


require __DIR__ . '/vendor/autoload.php';


$raw = file_get_contents('php://input');
$payload = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

$command = CommandFactory::fromPayload($payload);
$command->execute($payload);
