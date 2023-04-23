<?php

namespace Bidb97\CrossPosting\Http\Controllers;

use Bidb97\CrossPosting\Models\CrossPosting;

class ShortController
{
    /**
     * @param $shortUri
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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