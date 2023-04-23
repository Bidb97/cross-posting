<?php

namespace Bidb97\CrossPosting\Services;

use Bidb97\CrossPosting\Contracts\CrossPostingProvider;
use Bidb97\CrossPosting\Models\CrossPosting;

class Posting
{
    /**
     * @param CrossPosting $crossPosting
     */
    public function run(CrossPosting $crossPosting): void
    {
        foreach (config('cross-posting.posting_to') as $crossPostingProvider) {
            $this->share(new $crossPostingProvider($crossPosting));
        }

        $crossPosting->is_posted = true;
        $crossPosting->save();
    }

    /**
     * @param CrossPostingProvider $crossPostingProvider
     * @param CrossPosting $crossPosting
     */
    private function share(CrossPostingProvider $crossPostingProvider): void
    {
        $crossPostingProvider->share();
    }

}