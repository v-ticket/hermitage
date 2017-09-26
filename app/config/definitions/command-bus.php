<?php

use livetyping\hermitage\app\factories\CommandBus;
use livetyping\hermitage\foundation\bus\commands;
use livetyping\hermitage\foundation\bus\handlers;
use SimpleBus\Message\Bus\MessageBus;
use function DI\factory;
use function DI\get;
use function DI\object;

return [
    'command-bus.middleware' => [],

    'command-bus' => factory([CommandBus::class, 'create']),
    MessageBus::class => get('command-bus'),

    'command-bus.command-handler-map' => [
        commands\StoreImageCommand::class => handlers\StoreImageCommandHandler::class,
        commands\MakeImageVersionCommand::class => handlers\MakeImageVersionCommandHandler::class,

        commands\StoreFileCommand::class => handlers\StoreFileCommandHandler::class,
        commands\DeleteFileCommand::class => handlers\DeleteFileCommandHandler::class,
    ],

    // command handlers
    handlers\StoreImageCommandHandler::class => object(handlers\StoreImageCommandHandler::class),
    handlers\MakeImageVersionCommandHandler::class => object(handlers\MakeImageVersionCommandHandler::class),

    handlers\StoreFileCommandHandler::class => object(handlers\StoreFileCommandHandler::class),
    handlers\DeleteFileCommandHandler::class => object(handlers\DeleteFileCommandHandler::class),
];
