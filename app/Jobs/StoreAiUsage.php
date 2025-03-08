<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreAiUsage implements ShouldQueue
{
    use Queueable;

    protected $tries = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::connection('mongodb')->table('ai_usages')->insert([
            ...$this->data,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
