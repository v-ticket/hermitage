<?php

namespace livetyping\hermitage\app\actions;

use livetyping\hermitage\foundation\bus\commands\MakeImageVersionCommand;
use livetyping\hermitage\foundation\contracts\images\Storage;
use livetyping\hermitage\foundation\Util;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SimpleBus\Message\Bus\MessageBus;
use Slim\Http\Body;

/**
 * Class GetAction
 *
 * @package livetyping\hermitage\app\actions
 */
class GetAction
{
    /** @var \livetyping\hermitage\foundation\contracts\images\Storage */
    protected $storage;

    /** @var \SimpleBus\Message\Bus\MessageBus */
    protected $bus;

    /**
     * GetAction constructor.
     *
     * @param \livetyping\hermitage\foundation\contracts\images\Storage $storage
     * @param \SimpleBus\Message\Bus\MessageBus $bus
     */
    public function __construct(Storage $storage, MessageBus $bus)
    {
        $this->storage = $storage;
        $this->bus = $bus;
    }

    /**
     * @param string $filename
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \livetyping\hermitage\foundation\exceptions\FileNotFoundException
     */
    public function __invoke(string $filename, Request $request, Response $response): Response
    {
        $fileExt = array_shift(explode(':', end(explode('.', $filename))));
        if (in_array($fileExt, Util::getSupportedMimeTypes())) {
            $this->prepareImage($filename);
        } else {
            $filename = explode('.', $filename);
            $filename[count($filename)-1] = $fileExt;
            $filename = implode('.', $filename);
        }
        $file = $this->storage->get($filename);
        $body = new Body(fopen('php://temp', 'r+'));
        $body->write($file->getBinary());

        return $response->withHeader('Content-Type', $file->getMimeType())->withBody($body);
    }

    /**
     * @param string $filename
     * @throws \livetyping\hermitage\foundation\exceptions\ImageNotFoundException
     */
    protected function prepareImage(string $filename)
    {
        if (!Util::isOriginal($filename) && !$this->storage->has($filename)) {
            $this->makeImageVersion($filename);
        }
    }

    /**
     * @param string $filename
     *
     * @throws \livetyping\hermitage\foundation\exceptions\ImageNotFoundException
     */
    protected function makeImageVersion(string $filename)
    {
        $original = Util::original($filename);
        $command = new MakeImageVersionCommand($original, Util::version($filename));

        $this->bus->handle($command);
    }
}
