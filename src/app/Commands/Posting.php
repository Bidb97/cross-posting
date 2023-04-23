<?php

namespace Bidb97\CrossPosting\Commands;

use Bidb97\CrossPosting\Models\CrossPosting;
use Illuminate\Console\Command;

class Posting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cross-posting:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts the process of sending messages to social networks.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $crossPostings = CrossPosting::where([
                'is_posted' => false,
                ['publish_date', '<=', now()]
            ])
            ->orderBy('publish_date', 'asc')
            ->get();

        foreach ($crossPostings as $crossPosting) {
            (new \Bidb97\CrossPosting\Services\Posting())->run($crossPosting);
        }
    }
}
