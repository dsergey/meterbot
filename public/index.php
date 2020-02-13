<?php declare(strict_types=1);

use Dotenv\Dotenv;
use MeterDataBot\ApiRequest;
use MeterDataBot\Application;
use MeterDataBot\Commands;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

define('ROOT_DIR', dirname(__DIR__));

require(ROOT_DIR . '/vendor/autoload.php');
Dotenv::createImmutable(ROOT_DIR, '.env')->load();

try {
    $raw = file_get_contents('php://input');
    $payload = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

    $container = new ContainerBuilder();

    $container->register(HttpClientInterface::class, HttpClient::create());
    $container->register(ApiRequest::class, ApiRequest::class)
        ->addArgument($_ENV['API_URI'])
        ->addArgument($_ENV['BOT_SECRET'])
        ->addArgument(new Reference(HttpClientInterface::class));

    //ToDo: load dependencies auto from commands & handlers from config commands
    $container->autowire(Commands\Add::class, Commands\Add::class)
        ->setPublic(true);
    $container->autowire(Commands\AddressList::class, Commands\AddressList::class)
        ->setPublic(true);
    $container->autowire(Commands\System\UnsupportedCommand::class, Commands\System\UnsupportedCommand::class)
        ->setPublic(true);
    $container->autowire(Commands\Start::class, Commands\Start::class)
        ->setPublic(true);

    $container->compile();

    (new Application(
        $container,
        require(ROOT_DIR . '/config/commands.php')
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

