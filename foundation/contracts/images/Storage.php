<?php

namespace livetyping\hermitage\foundation\contracts\images;

use livetyping\hermitage\foundation\entities\File;

/**
 * Interface Storage
 *
 * @package livetyping\hermitage\foundation\contracts\images
 */
interface Storage
{
    /**
     * @param string $path
     *
     * @return File
     * @throws \livetyping\hermitage\foundation\exceptions\FileNotFoundException
     */
    public function get(string $path): File;

    /**
     * @param string $path
     *
     * @return bool
     */
    public function has(string $path): bool;

    /**
     * @param File $file
     *
     * @return void
     */
    public function put(File $file);

    /**
     * @param File $file
     *
     * @return void
     * @throws \livetyping\hermitage\foundation\exceptions\FileNotFoundException
     */
    public function delete(File $file);
}
