<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "ftp", "s3", "rackspace"
    |
    */

//	'default' => 'local',
    'default' => env('FILESYSTEM_DEFAULT', 'local'),

    'imageLocation' => 'images',
    'imageUrl' => (env('FILESYSTEM_DEFAULT', 'local')=='s3')?
        env('S3_URL', 'your-url').env('S3_BUCKET', 'your-bucket').'/images/'
        :(env('FILESYSTEM_DEFAULT', 'local')=='google')?
            env('GOOGLE_URL', 'your-url').env('GOOGLE_BUCKET', 'your-bucket').'/images/'
            :'/images/',

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

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        'ftp' => [
            'driver'   => 'ftp',
            'host'     => 'ftp.example.com',
            'username' => 'your-username',
            'password' => 'your-password',

            // Optional FTP Settings...
            // 'port'     => 21,
            // 'root'     => '',
            // 'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
        ],

        's3' => [
            'driver' => 's3',
            'key'    => env('S3_KEY', 'your-key'),
            'secret' => env('S3_SECRET', 'your-secret'),
            'region' => env('S3_REGION', 'your-region'),
            'bucket' => env('S3_BUCKET', 'your-bucket'),
        ],

        'google' => [
            'driver' => 's3',
            'key'    => env('GOOGLE_KEY', 'your-key'),
            'secret' => env('GOOGLE_SECRET', 'your-secret'),
            'region' => env('GOOGLE_REGION', 'your-region'),
            'bucket' => env('GOOGLE_BUCKET', 'your-bucket'),
        ],

        'gcs' => [
                // Select the Google Cloud Storage Disk
            'driver'                               => 'gcs',
                // The id of your new service account
            'service_account'                      => env('GOOGLE_ACCOUNT', 'service@account.iam.gserviceaccount.com'),
                // The location of the p12 service account certificate
            'service_account_certificate'          => storage_path() . env('GOOGLE_CERTIFICATE', '/credentials.p12'),
                // The password you will be given when creating the service account
            'service_account_certificate_password' => env('GOOGLE_SECRET', 'your-secret'),
                // The bucket you want this disk to point at
            'bucket'                               => env('GOOGLE_BUCKET', 'cloud-storage-bucket'),
            'url'                               => env('GOOGLE_URL', 'url'),
        ],

        'rackspace' => [
            'driver'    => 'rackspace',
            'username'  => 'your-username',
            'key'       => 'your-key',
            'container' => 'your-container',
            'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'IAD',
            'url_type'  => 'publicURL',
        ],

    ],

];
