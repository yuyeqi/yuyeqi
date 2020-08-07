<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'oss'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'admin' => [
            'driver' => 'local',
            'root' => storage_path('app/public/upload/admin/') . date('Ymd', time()),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],
        'oss' => [
            'driver' => 'oss',
            'access_id' => 'LTAI4GCvB4Q5vyiGGqYzqQNe',
            'access_key' => '8ek1F4KWqIfsqjczMw7rmpGvBYouD9',
            'bucket' => 'hpmc',
            //'endpoint' => 'oss-cn-beijing-internal.aliyuncs.com', // OSS 外网节点或自定义外部域名
            'endpoint' => 'oss-cn-beijing.aliyuncs.com', // OSS 外网节点或自定义外部域名
            //'endpoint_internal' =>
            'cdnDomain' => false,
            'ssl' => false,
            'isCName' => false,
        ],

    ],

];
