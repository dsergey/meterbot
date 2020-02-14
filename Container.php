<?php

namespace MeterDataBot;

use MeterDataBot\Commands\System\Dialog;
use MeterDataBot\Commands\System\UnsupportedCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Container extends ContainerBuilder
{
    protected $supportedCommands;

    public function __construct(array $supportedCommands = [])
    {
        $this->supportedCommands = $supportedCommands;
        parent::__construct();
    }

    public function build(): ContainerInterface
    {
        $this->compile();

        return $this;
    }

    public function withAllDependencies(): self
    {
        $this->registerSystemDependencies();
        $this->registerSystemCommands();
        $this->registerCustomCommands();

        return $this;
    }

    public function registerSystemDependencies(): self
    {
        $this->register(HttpClientInterface::class, HttpClient::create());
        $this->register(ApiRequest::class, ApiRequest::class)
            ->addArgument($_ENV['API_URI'])
            ->addArgument($_ENV['BOT_SECRET'])
            ->addArgument(new Reference(HttpClientInterface::class));

        return $this;
    }

    public function registerSystemCommands(): self
    {
        $this->autowire(UnsupportedCommand::class, UnsupportedCommand::class)
            ->setPublic(true);

        $this->autowire(Dialog::class, Dialog::class)
            ->setPublic(true);

        return $this;
    }

    public function registerCustomCommands(): self
    {
        foreach ($this->supportedCommands as $supportedCommandHandlers) {
            if (!is_array($supportedCommandHandlers)) {
                $supportedCommandHandlers = [$supportedCommandHandlers];
            }

            foreach ($supportedCommandHandlers as $handler) {
                $this->autowire($handler, $handler)->setPublic(true);
            }
        }

        return $this;
    }
}