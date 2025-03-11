<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

class FixBugsJob implements ShouldQueue
{
    use Queueable;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call('miniprometeo:fix-bugs-command');
    }
}
