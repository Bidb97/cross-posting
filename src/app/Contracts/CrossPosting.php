<?php

namespace Bidb97\CrossPosting\Contracts;

interface CrossPosting
{
    /**
     * @return array
     */
    public function getDataMapping(): array;

    /**
     * @return string
     */
    public function getResourceUri(): string;
}