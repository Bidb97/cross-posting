<?php

namespace Bidb97\CrossPosting\Http\Controllers;

use Bidb97\CrossPosting\Models\CrossPosting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class ShortController
{
    /**
     * @param $shortUri
     * @return Application|RedirectResponse|Redirector
     */
    public function __invoke($shortUri)
    {
        $crossPosting = CrossPosting::select('resource_uri')
            ->where('short_uri', $shortUri)
            ->limit(1)
            ->firstOrFail();

        return redirect($crossPosting->resource_uri);
    }
}