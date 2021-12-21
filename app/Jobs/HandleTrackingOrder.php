<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleTrackingOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dataOrder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dataOrder)
    {
        $this->dataOrder = $dataOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        app('App\Services\WebhookOrderShopifyService')->handleTrackingConversion($this->dataOrder);
    }
}
