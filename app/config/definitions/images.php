<?php

use Aws\S3\S3Client;
use livetyping\hermitage\foundation\contracts\images\Generator as GeneratorContract;
use livetyping\hermitage\foundation\contracts\images\Processor as ProcessorContract;
use livetyping\hermitage\foundation\contracts\images\Storage as StorageContract;
use livetyping\hermitage\foundation\images\Generator;
use livetyping\hermitage\foundation\images\processor\Processor;
use livetyping\hermitage\foundation\images\Storage;
use Interop\Container\ContainerInterface;
use Intervention\Image\ImageManager;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory;
use League\Flysystem\Filesystem;
use function DI\env;
use function DI\get;
use function DI\object;
use function DI\string;

return [
    'images.versions'                               => [
        'mini'  => [
            'type'   => 'resize',
            'height' => 200,
            'width'  => 200,
        ],
        'small' => [
            'type'   => 'resize',
            'height' => 400,
            'width'  => 400,
        ],
        'thumb' => [
            'type'   => 'fit',
            'height' => 100,
            'width'  => 100,
        ],
    ],
    'images.optimization-params'                    => ['maxHeight' => 800, 'maxWidth' => 800],
    'images.manipulator-map'                        => [],
    'images.manager-config'                         => ['driver' => 'gd'],

    // processor
    'images.processor.manager'                      => object(ImageManager::class)
        ->constructor(get('images.manager-config')),
    'images.processor'                              => object(Processor::class)
        ->constructor(get('images.processor.manager'))
        ->method('addManipulatorMap', get('images.manipulator-map'))
        ->method('setVersions', get('images.versions'))
        ->method('setOptimizationParams', get('images.optimization-params')),
    ProcessorContract::class                        => get('images.processor'),

    // generator
    'images.generator'                              => object(Generator::class),
    GeneratorContract::class                        => get('images.generator'),

    // storage
    'images.storage.adapter'                        => env('STORAGE_ADAPTER', 'local'),
    'images.storage'                                => function (ContainerInterface $c) {
        $adapter = $c->get('images.storage.adapter');
        $adapter = $c->get("images.storage.adapters.{$adapter}");
        $adapter = new CachedAdapter($adapter, new Memory());

        return new Storage(new Filesystem($adapter));
    },
    StorageContract::class                          => get('images.storage'),

    // local adapter
    'images.storage.adapters.local'                 => object(Local::class)->constructor(string('{storage-dir}/')),

    // aws s3 adapter
    'images.storage.adapters.s3'                    => object(AwsS3Adapter::class)
        ->constructor(get('images.storage.adapters.s3.client'), env('STORAGE_S3_BUCKET')),
    'images.storage.adapters.s3.client'             => object(S3Client::class)
        ->constructor(get('images.storage.adapters.s3.client-config')),
    'images.storage.adapters.s3.client-config'      => [
        'version'     => '2006-03-01',
        'region'      => env('STORAGE_S3_REGION'),
        'credentials' => get('images.storage.adapters.s3.client-credentials'),
    ],
    'images.storage.adapters.s3.client-credentials' => [
        'key'    => env('STORAGE_S3_KEY'),
        'secret' => env('STORAGE_S3_SECRET'),
    ],
];
