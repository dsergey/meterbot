<?php /** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);


namespace MeterDataBot\Tests;

use MeterDataBot\Application;
use MeterDataBot\Commands\System\Dialog;
use MeterDataBot\Commands\System\UnsupportedCommand;
use MeterDataBot\Exceptions\EmptyMessageText;
use MeterDataBot\Exceptions\InvalidCommandHandler;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ApplicationTest extends TestCase
{
    public function testSetDialogHandlerWhenGivenWrongClasses(): void
    {
        $app = new Application($this->createMock(ContainerInterface::class), []);

        $this->expectException(InvalidCommandHandler::class);

        $app->setDialogHandler(TestCase::class);
    }

    public function testSetUnsupportedCommandHandlerWhenGivenWrongClasses(): void
    {
        $app = new Application($this->createMock(ContainerInterface::class), []);

        $this->expectException(InvalidCommandHandler::class);
        $app->setUnsupportedCommandHandler(TestCase::class);
    }

    public function testHandleWhenEmptyPayload(): void
    {
        $app = new Application($this->createMock(ContainerInterface::class), []);
        $this->expectException(EmptyMessageText::class);
        $app->handle([]);
    }

    public function testHandleWhenItsNotCommand(): void
    {
        $dialogHandler = $this->createMock(Dialog::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturn($dialogHandler);

        $app = new Application($container, []);

        $payload = ['message' => ['text' => 'it is not command']];

        $dialogHandler->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($payload));

        $app->setDialogHandler(Dialog::class);
        $app->handle($payload);
    }

    public function testHandleWhenCommandUnsupported(): void
    {
        $unsupportedCommandHandler = $this->createMock(UnsupportedCommand::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturn($unsupportedCommandHandler);

        $app = new Application($container, []);

        $payload = ['message' => ['text' => '/testUnsupportedCommandDSSDFDKSHFUGFDSGFDSGF']];

        $unsupportedCommandHandler->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($payload));

        $app->setUnsupportedCommandHandler(UnsupportedCommand::class);
        $app->handle($payload);
    }

    public function testHandleWhenCommandHandlersSetAsArray(): void
    {
        $testCommandHandler = $this->createMock(Dialog::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturn($testCommandHandler);

        $app = new Application($container, ['/start' => [Dialog::class]]);

        $payload = ['message' => ['text' => '/start']];

        $testCommandHandler->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($payload));

        $app->handle($payload);
    }

    public function testHandleWhenForCommandSetOnlyOneHandler(): void
    {
        $testCommandHandler = $this->createMock(Dialog::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturn($testCommandHandler);

        $app = new Application($container, ['/start' => Dialog::class]);

        $payload = ['message' => ['text' => '/start']];

        $testCommandHandler->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($payload));

        $app->handle($payload);
    }

}