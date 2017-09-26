<?php
/**
 * Created by PhpStorm.
 * User: gleb.dresviannikov@gmail.com
 * Date: 9/25/2017
 * Time: 02:14
 */

namespace livetyping\hermitage\foundation\entities;

use livetyping\hermitage\foundation\Util;

/**
 * Class File
 * @package livetyping\hermitage\foundation\entities
 */
class File
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $dirname;

    /** @var string */
    protected $mimeType;

    /** @var string */
    protected $extension;

    /** @var string */
    protected $binary;


    /**
     * Image constructor.
     *
     * @param string $binary
     * @param string $extension
     * @param string $path
     */
    public function __construct(string $binary, string $extension, string $path)
    {
        $this->binary = $binary;
        $this->extension = $extension;
        $this->name = Util::name($path);
        $this->dirname = Util::dirname($path);
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return (string)$this->mimeType;
    }

    /**
     * @return string
     */
    public function getBinary(): string
    {
        return (string)$this->binary;
    }

    /**
     * @return string
     */
    public function getDirname(): string
    {
        return (string)$this->dirname;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return Util::path(
            (string)$this->dirname,
            (string)$this->name,
            (string)$this->extension
        );
    }
}
