<?php

use Bidb97\CrossPosting\Services\SocialProviders;

return [

    'posting_to' => [
        SocialProviders\Telegram::class,
        SocialProviders\Vk::class,
        SocialProviders\Ok::class
    ],

    'social_providers' => [

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

    ]

];
