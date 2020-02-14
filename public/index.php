<?php declare(strict_types=1);

use Dotenv\Dotenv;
use MeterDataBot\Application;
use MeterDataBot\Container;

define('ROOT_DIR', dirname(__DIR__));

require(ROOT_DIR . '/vendor/autoload.php');
Dotenv::createImmutable(ROOT_DIR, '.env')->load();

try {
    $raw = file_get_contents('php://input');
    $payload = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    $supportedCommands = require(ROOT_DIR . '/config/commands.php');

    (new Application(
        (new Container($supportedCommands))->withAllDependencies()->build(),
        $supportedCommands
    ))
        ->handle($payload);
} catch (Throwable $throwable) {
    if ($_ENV['APP_DEBUG']) {
        /** @noinspection PhpUnhandledExceptionInspection */
        throw $throwable;
    }

    http_response_code(400);
    die('Bad Request');
}

