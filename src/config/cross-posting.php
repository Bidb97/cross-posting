<?php

use Bidb97\CrossPosting\Services\CrossPostingProviders;

return [

    /*
    |--------------------------------------------------------------------------
    | Social providers
    |--------------------------------------------------------------------------
    |
    | Services for sending data to socials networks
    | When adding a new social network, add the social provider to this list
    |
    */
    'posting_to' => [
        CrossPostingProviders\Telegram::class,
        CrossPostingProviders\Vk::class,
        CrossPostingProviders\Ok::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Social providers configs
    |--------------------------------------------------------------------------
    |
    | Social networks configs, configs are presented in the form of arrays,
    | for working with multiple social network accounts
    | When adding a new social network, add the social provider to this list
    |
    */
    'configs' => [

        'telegram' => [
            [
                'bot_token' => env('CROSS_POSTING_TELEGRAM_BOT_TOKEN'),
                'chat_id' => env('CROSS_POSTING_TELEGRAM_CHAT_ID')
            ]
        ],

        'vk' => [

        ],

        'ok' => [

        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | Proxy
    |--------------------------------------------------------------------------
    |
    | To send requests through a proxy
    | Examples:
    | - http://localhost:8125
    | - http://username:password@localhost:8125
    |
    */
    'proxy' => env('CROSS_POSTING_PROXY'),

    /*
    |--------------------------------------------------------------------------
    | Short Link length
    |--------------------------------------------------------------------------
    |
    */
    'short_link_length' => env('CROSS_POSTING_SHORT_LINK_LENGTH', 8),

    /*
    |--------------------------------------------------------------------------
    | Short Link Path
    |--------------------------------------------------------------------------
    |
    */
    'short_link_path' => env('CROSS_POSTING_SHORT_LINK_PATH', 'short'),

];
