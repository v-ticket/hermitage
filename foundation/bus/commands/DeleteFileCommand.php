<?php

namespace livetyping\hermitage\foundation\bus\commands;

/**
 * Class DeleteFileCommand
 *
 * @package livetyping\hermitage\foundation\bus\commands
 */
final class DeleteFileCommand
{
    /** @var string */
    protected $path;

    /**
     * DeleteFileCommand constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
