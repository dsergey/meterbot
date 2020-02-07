<?php

use Dotenv\Dotenv;
use MeterDataBot\Commands\CommandFactory;

require (__DIR__ . '/vendor/autoload.php');

//try {
    $raw = file_get_contents('php://input');
    $payload = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

    Dotenv::createImmutable(__DIR__, '.env')->load();

    //Container::registerDependencies();
    $command = CommandFactory::fromPayload($payload);
    $command->execute($payload);
//
//} catch (Throwable $throwable) {
//    http_response_code(400);
//    die('Bad Request');
//}

