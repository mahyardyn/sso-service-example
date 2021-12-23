<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/lumen.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/lumen.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Lumen Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'otp' => [
            'driver' => 'daily',
            'path' => storage_path('logs/otp/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'exception' => [
            'driver' => 'daily',
            'path' => storage_path('logs/exception/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'register' => [
            'driver' => 'daily',
            'path' => storage_path('logs/register/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'create' => [
            'driver' => 'daily',
            'path' => storage_path('logs/create/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'edit' => [
            'driver' => 'daily',
            'path' => storage_path('logs/edit/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'delete' => [
            'driver' => 'daily',
            'path' => storage_path('logs/delete/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'check-jwt' => [
            'driver' => 'daily',
            'path' => storage_path('logs/check-jwt/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'login' => [
            'driver' => 'daily',
            'path' => storage_path('logs/login/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'logout' => [
            'driver' => 'daily',
            'path' => storage_path('logs/logout/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'update-profile' => [
            'driver' => 'daily',
            'path' => storage_path('logs/update-profile/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'change-permission' => [
            'driver' => 'daily',
            'path' => storage_path('logs/change-permission/log.log'),
            'level' => 'debug',
            'days' => 90,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],
    ],

];
