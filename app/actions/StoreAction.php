<?php

namespace livetyping\hermitage\app\actions;

use livetyping\hermitage\app\exceptions\BadRequestException;
use livetyping\hermitage\foundation\bus\commands\StoreFileCommand;
use livetyping\hermitage\foundation\bus\commands\StoreImageCommand;
use livetyping\hermitage\foundation\Util;
use Psr\Http\Message\ServerRequestInterface as Request;
use SimpleBus\Message\Bus\MessageBus;
use Slim\Http\Response;

/**
 * Class StoreAction
 *
 * @package livetyping\hermitage\app\actions
 */
class StoreAction
{
    /** @var \SimpleBus\Message\Bus\MessageBus */
    protected $bus;

    protected $container;

    /**
     * StoreAction constructor.
     *
     * @param \SimpleBus\Message\Bus\MessageBus $bus
     */
    public function __construct(MessageBus $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     * @throws \livetyping\hermitage\app\exceptions\BadRequestException
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $mime = (string)current($request->getHeader('Content-Type'));
        $binary = (string)$request->getBody();
        $extension = $request->getHeaderLine('extension');
        if (empty($binary)) {
            throw new BadRequestException('Invalid body.');
        } elseif (!empty($mime) && in_array($mime, Util::supportedMimeTypes())) {
            $command = new StoreImageCommand($mime, $binary);
        } elseif (!empty($extension)) {
            $command = new StoreFileCommand($extension, $binary);
        } else {
            throw new BadRequestException('Invalid mime-type or extension');
        }

        $this->bus->handle($command);

        return $response->withStatus(201)->withJson(['filename' => $command->getPath()]);
    }
}
