<?php
/**
 * Created by PhpStorm.
 * User: g.dresviannykov
 * Date: 26.09.2017
 * Time: 12:48
 */

namespace livetyping\hermitage\foundation\exceptions;

class FileNotFoundException extends Exception
{
    /** @var string */
    protected $path;

    /**
     * FileNotFoundException constructor.
     *
     * @param string     $path
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($path, $code = 0, \Exception $previous = null)
    {
        $this->path = $path;

        parent::__construct('File not found at path: ' . $this->getPath(), $code, $previous);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
