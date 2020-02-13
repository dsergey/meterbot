<?php declare(strict_types=1);

namespace MeterDataBot;

use MeterDataBot\Commands\System\CommandInterface;
use MeterDataBot\Commands\System\Dialog;
use MeterDataBot\Commands\System\UnsupportedCommand;
use MeterDataBot\Exceptions\EmptyMessageText;
use MeterDataBot\Exceptions\InvalidCommandHandler;
use Psr\Container\ContainerInterface;
use Throwable;

class Application
{
    private $supportedCommands;

    private $diContainer;

    private $dialogHandler = Dialog::class;

    private $unsupportedCommandHandler = UnsupportedCommand::class;

    public function __construct(ContainerInterface $diContainer, array $supportedCommands)
    {
        $this->diContainer = $diContainer;
        $this->supportedCommands = $supportedCommands;
    }

    /**
     * @param string $className
     *
     * @return $this
     *
     * @throws InvalidCommandHandler
     */
    public function setDialogHandler(string $className): self
    {
        $this->ensureClassType($className);
        $this->dialogHandler = $className;

        return $this;
    }

    /**
     * @param string $className
     *
     * @return $this
     *
     * @throws InvalidCommandHandler
     */
    public function setUnsupportedCommandHandler(string $className): self
    {
        $this->ensureClassType($className);
        $this->unsupportedCommandHandler = $className;

        return $this;
    }

    /**
     * @param array $payload
     *
     * @throws Throwable
     */
    public function handle(array $payload): void
    {
        $command = $this->extractCommand($this->getTextFromPayload($payload));

        if (!$command) {
            $this->createCommandObject($this->dialogHandler)->execute($payload);

            return;
        }

        if ($this->isUnsupportedCommand($command)) {
            $this->createCommandObject($this->unsupportedCommandHandler)->execute($payload);

            return;
        }

        foreach ($this->getCommandHandlers($command) as $commandHandler) {
            $commandHandler->execute($payload);
        }
    }

    /**
     * @param array $payload
     *
     * @return string
     *
     * @throws EmptyMessageText
     */
    private function getTextFromPayload(array $payload): string
    {
        if (empty($payload['message']['text'])) {
            throw new EmptyMessageText('The text in incoming message is empty');
        }

        return $payload['message']['text'];
    }

    private function extractCommand(string $string): string
    {
        if (!$this->isCommand($string)) {
            return '';
        }

        $parts = explode(' ', $string);

        return empty($parts[0]) ? '' : strtolower(trim($parts[0]));
    }

    /**
     * Is it command. Commands start from / (slash)
     *
     * @param string $string
     *
     * @return bool
     */
    private function isCommand(string $string): bool
    {
        return strncmp($string, '/', 1) === 0;
    }

    private function createCommandObject(string $class): CommandInterface
    {
        return $this->diContainer->get($class);
    }

    private function isUnsupportedCommand(string $command): bool
    {
        return empty($this->supportedCommands[$command]);
    }

    /**
     * @param string $command
     *
     * @return CommandInterface[]
     */
    private function getCommandHandlers(string $command): iterable
    {
        if (!is_array($this->supportedCommands[$command])) {
            $this->supportedCommands[$command] = [$this->supportedCommands[$command]];
        }

        foreach ($this->supportedCommands[$command] as $commandClass) {
            yield $this->createCommandObject($commandClass);
        }
    }

    /**
     * @param string $className
     *
     * @throws InvalidCommandHandler
     */
    private function ensureClassType(string $className): void
    {
        if (!in_array(CommandInterface::class, class_implements($className), true)) {
            throw new InvalidCommandHandler($className . ' must implement ' . CommandInterface::class);
        }
    }
}