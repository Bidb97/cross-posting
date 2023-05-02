<?php

namespace Bidb97\CrossPosting\Services\CrossPostingProviders;

use Bidb97\CrossPosting\Contracts\CrossPostingProvider;
use Bidb97\CrossPosting\Models\CrossPosting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class Telegram implements CrossPostingProvider
{
    /**
     * @var CrossPosting
     */
    private $crossPosting;

    /**
     * @var
     */
    private $configs;

    /**
     * @param CrossPosting $crossPosting
     */
    public function __construct(CrossPosting $crossPosting)
    {
        $this->crossPosting = $crossPosting;
        $this->configs = config('cross-posting.configs.telegram');
    }

    public function share(): void
    {
        $shortUri = route('cross-posting:short.show', ['shortUri' => $this->crossPosting->short_uri]);

        $message = "*" . $this->crossPosting->posting_data['title'] . "*" . "\r\n"
                    . Str::limit(strip_tags($this->crossPosting->posting_data['content']), 5000) . "\r\n"
                    . __('cross-posting::messages.read_more')
                    . "[" . $shortUri . "](" . $shortUri . ")";

        foreach ($this->configs as $config) {

            $requestParams = [
                'chat_id' => $config['chat_id'],
                'parse_mode' => 'markdown'
            ];

            if (!empty($this->crossPosting->posting_data['image'])) {
                $method = 'sendPhoto';
                $requestParams['photo'] = asset($this->crossPosting->posting_data['image']);
                $requestParams['caption'] = $message;
            } else {
                $method = 'sendMessage';
                $requestParams['text'] = $message;
            }

            Http::asForm()
                ->when(!empty(config('cross-posting.proxy')), function ($http) {
                    $http->withOptions([
                        'proxy' => config('cross-posting.proxy')
                    ]);
                })
                ->post('https://api.telegram.org/bot' . $config['bot_token'] . '/' . $method , $requestParams);

            sleep(2);
        }
    }
}