<?php

namespace livetyping\hermitage\foundation\bus\handlers;

use livetyping\hermitage\foundation\bus\commands\DeleteFileCommand;
use livetyping\hermitage\foundation\contracts\images\Storage;

/**
 * Class DeleteImageCommandHandler
 *
 * @package livetyping\hermitage\foundation\bus\handlers
 */
final class DeleteFileCommandHandler
{
    /** @var \livetyping\hermitage\foundation\contracts\images\Storage */
    protected $storage;

    /**
     * DeleteImageCommandHandler constructor.
     *
     * @param \livetyping\hermitage\foundation\contracts\images\Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param DeleteFileCommand $command
     */
    public function handle(DeleteFileCommand $command)
    {
        $image = $this->storage->get($command->getPath());
        $this->storage->delete($image);
    }
}
