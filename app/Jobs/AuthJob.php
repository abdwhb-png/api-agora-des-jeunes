<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class AuthJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public $user) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Http::post(config('app.home_webhook_base') . '/new-login', ['user' => $this->user]);
    }
}
