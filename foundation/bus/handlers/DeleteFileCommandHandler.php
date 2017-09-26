<?php

namespace livetyping\hermitage\foundation\bus\handlers;

use livetyping\hermitage\foundation\bus\commands\DeleteFileCommand;
use livetyping\hermitage\foundation\contracts\images\Storage;

/**
 * Class DeleteFileCommandHandler
 *
 * @package livetyping\hermitage\foundation\bus\handlers
 */
final class DeleteFileCommandHandler
{
    /** @var \livetyping\hermitage\foundation\contracts\images\Storage */
    protected $storage;

    /**
     * DeleteFileCommandHandler constructor.
     *
     * @param \livetyping\hermitage\foundation\contracts\images\Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param DeleteFileCommand $command
     * @throws \livetyping\hermitage\foundation\exceptions\FileNotFoundException
     */
    public function handle(DeleteFileCommand $command)
    {
        $file = $this->storage->get($command->getPath());
        $this->storage->delete($file);
    }
}
