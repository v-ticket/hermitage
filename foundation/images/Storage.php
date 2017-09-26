<?php

namespace livetyping\hermitage\foundation\images;

use livetyping\hermitage\foundation\contracts\images\Storage as StorageContract;
use livetyping\hermitage\foundation\entities\File;
use livetyping\hermitage\foundation\entities\Image;
use livetyping\hermitage\foundation\exceptions\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use livetyping\hermitage\foundation\Util;

/**
 * Class Storage
 *
 * @package livetyping\hermitage\foundation\images
 */
final class Storage implements StorageContract
{
    /** @var \League\Flysystem\FilesystemInterface */
    protected $filesystem;

    /**
     * Storage constructor.
     *
     * @param \League\Flysystem\FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $path
     * @return File
     * @throws FileNotFoundException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function get(string $path): File
    {
        $this->assertPresent($path);

        $fileExt = array_shift(explode(':', end(explode('.', $path))));

        if (in_array($fileExt, Util::getSupportedMimeTypes())) {
            $fileClass = Image::class;
        } else {
            $fileClass = File::class;
        }

        $image = new $fileClass(
            $this->filesystem->read($path),
            $this->filesystem->getMimetype($path),
            $path
        );

        return $image;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function has(string $path): bool
    {
        return $this->filesystem->has($path);
    }

    /**
     * @param File $file
     */
    public function put(File $file)
    {
        $this->filesystem->put(
            $file->getPath(),
            $file->getBinary(),
            ['mimetype' => $file->getMimeType()]
        );
    }

    /**
     * @param File $file
     *
     * @throws \livetyping\hermitage\foundation\exceptions\FileNotFoundException
     */
    public function delete(File $file)
    {

        $this->assertPresent($file->getPath());

        $this->filesystem->deleteDir($file->getDirname());
    }

    /**
     * @param string $path
     *
     * @throws \livetyping\hermitage\foundation\exceptions\FileNotFoundException
     */
    protected function assertPresent(string $path)
    {
        if (!$this->has($path)) {
            throw new FileNotFoundException($path);
        }
    }
}
