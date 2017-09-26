<?php

namespace livetyping\hermitage\foundation\bus\commands;

/**
 * Class StoreFileCommand
 *
 * @package livetyping\hermitage\foundation\bus\commands
 */
final class StoreFileCommand
{
    /** @var string */
    protected $extension;
    
    /** @var string */
    protected $binary;
    
    /** @var string */
    protected $path;

    /**
     * StoreFileCommand constructor.
     *
     * @param string $extension
     * @param string $binary
     */
    public function __construct(string $extension, string $binary)
    {
        $this->extension = $extension;
        $this->binary = $binary;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getBinary(): string
    {
        return $this->binary;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return (string)$this->path;
    }
    
    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }
}
