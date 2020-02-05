<?php declare(strict_types=1);

namespace MeterDataBot\Tests;

use MeterDataBot\Commands\CommandFactory;
use MeterDataBot\Commands\NullCommand;
use PHPUnit\Framework\TestCase;

class CommandFactoryTest extends TestCase
{
    /**
     * @dataProvider wrongPayloadsDataProvider
     *
     * @param array $payload
     */
    public function testFromPayloadWithWrongPayloads(array $payload): void
    {
        $this->assertInstanceOf(NullCommand::class, CommandFactory::fromPayload($payload));
    }

    /**
     * @dataProvider validPayloadsDataProvider
     *
     * @param array $payload
     */
    public function testFromPayloadWithValidPayloads(array $payload): void
    {
        $this->assertNotInstanceOf(NullCommand::class, CommandFactory::fromPayload($payload));
    }

    public function wrongPayloadsDataProvider(): array
    {
        return [
            'Empty Payload' => [[]],
            'Empty Text key in Payload' => [['message' => ['text' => '']]],
            'Wrong Text key as a Command' => [['message' => ['text' => 'WRONG COMMAND']]],
            'Wrong Command' => [['message' => ['text' => '/start_command']]],
            'Wrong Command Just Slash' => [['message' => ['text' => '/']]],
            'Wrong Command Digits' => [['message' => ['text' => '/start42']]],
            'Command With Spaces' => [['message' => ['text' => '/ start']]],
            'Valid Command but not supported' => [['message' => ['text' => '/unsupportedcommand']]],
        ];
    }

    public function validPayloadsDataProvider(): array
    {
        return [
            'Start Command' => [['message' => ['text' => '/start']]],
            'Start Command and param 2 spaces' => [['message' => ['text' => '/start  params']]],
            'Start Command with params' => [['message' => ['text' => '/start param1 param2']]],
        ];
    }
}