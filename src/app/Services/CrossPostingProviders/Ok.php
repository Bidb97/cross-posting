<?php

namespace Bidb97\CrossPosting\Services\CrossPostingProviders;

use Bidb97\CrossPosting\Contracts\CrossPostingProvider;
use Bidb97\CrossPosting\Models\CrossPosting;

class Ok implements CrossPostingProvider
{
    public function __construct(CrossPosting $crossPosting)
    {

    }

    public function share(): void
    {
        // TODO: Implement posting() method.
    }


}