<?php

namespace Bidb97\CrossPosting\Contracts;

use Bidb97\CrossPosting\Models\CrossPosting;

interface CrossPostingProvider
{
    /**
     * @param CrossPosting $crossPosting
     */
    public function __construct(CrossPosting $crossPosting);

    /**
     * Share on social network
     */
    public function share(): void;
}