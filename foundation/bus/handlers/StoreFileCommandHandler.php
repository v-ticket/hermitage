<?php

namespace livetyping\hermitage\foundation\bus\handlers;

use livetyping\hermitage\foundation\bus\commands\StoreFileCommand;
use livetyping\hermitage\foundation\contracts\images\Generator;
use livetyping\hermitage\foundation\contracts\images\Processor;
use livetyping\hermitage\foundation\contracts\images\Storage;
use livetyping\hermitage\foundation\entities\File;

/**
 * Class StoreFileCommandHandler
 *
 * @package livetyping\hermitage\foundation\bus\handlers
 */
final class StoreFileCommandHandler
{
    /** @var \livetyping\hermitage\foundation\contracts\images\Storage */
    protected $storage;

    /** @var \livetyping\hermitage\foundation\contracts\images\Processor */
    protected $processor;

    /** @var \livetyping\hermitage\foundation\contracts\images\Generator */
    protected $generator;

    /**
     * StoreFileCommandHandler constructor.
     *
     * @param \livetyping\hermitage\foundation\contracts\images\Storage   $storage
     * @param \livetyping\hermitage\foundation\contracts\images\Processor $processor
     * @param \livetyping\hermitage\foundation\contracts\images\Generator $generator
     */
    public function __construct(Storage $storage, Processor $processor, Generator $generator)
    {
        $this->storage = $storage;
        $this->processor = $processor;
        $this->generator = $generator;
    }

    /**
     * @param StoreFileCommand $command
     */
    public function handle(StoreFileCommand $command)
    {
        $file = new File($command->getBinary(), $command->getExtension(), $this->generator->path());
        $this->storage->put($file);
        $command->setPath($file->getPath());
    }
}
