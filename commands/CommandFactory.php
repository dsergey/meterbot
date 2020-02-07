<?php

namespace MeterDataBot\Commands;

class CommandFactory
{
    private const COMMAND_DELIMITER = ' ';

    public function __construct()
    {
        //register and load DI container
    }

    public static function fromPayload(array $payload): CommandInterface
    {
        $factory = new static();

        if (empty($payload['message']['text'])) {
            return $factory->createCommandObject(NullCommand::class);
        }

        $command = strtolower($factory->extractCommand($payload['message']['text']));
        $className = '\MeterDataBot\Commands\\' . $command;

        if (!$command || !class_exists($className)) {
            return $factory->createCommandObject(NullCommand::class);
        }

        return $factory->createCommandObject($className);
    }

    public function createCommandObject(string $classname): CommandInterface
    {
        return new $classname();
    }

    private function extractCommand(string $string): string
    {
        if (strncmp($string, '/', 1) !== 0) {
            return '';
        }

        $parts = explode(static::COMMAND_DELIMITER, substr($string, 1));

        return empty($parts[0]) ? '' : trim($parts[0]);
    }
}