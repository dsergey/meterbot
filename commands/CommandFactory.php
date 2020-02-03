<?php

namespace MeterDataBot\Commands;

class CommandFactory
{
    public static function fromPayload(array $payload): CommandInterface
    {
        if (empty($payload['message']['text'])) {
            return self::exitWithLog($payload);
        }

        $command = $payload['message']['text'];

        if (strncmp($command, '/', 1) !== 0) {
            return self::exitWithLog($payload);
        }
//todo parse via regex
        $command = substr($command, 1);

        if ($command === false) {
            return self::exitWithLog($payload);
        }

        $command = strtolower($command);
        $className = '\MeterDataBot\Commands\\' . $command;

        if (!class_exists($className)) {
            return self::exitWithLog($payload);
        }

        return new $className();
    }

    /**
     * @param array $payload
     *
     * @return NullCommand
     */
    private static function exitWithLog(array $payload): NullCommand
    {
        file_put_contents('error', var_export($payload), FILE_APPEND);

        return new NullCommand();
    }
}