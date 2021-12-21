<?php

namespace DoubleC\LaravelShopify\Events;

use DoubleC\LaravelShopify\Models\Shop;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppUninstalledEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     * @param Shop $shop
     */
    public function __construct(
        private Shop $shop
    )
    {
    }

    /**
     * Get shop uninstalled
     * @return Shop
     */
    public function shop(): Shop
    {
        return $this->shop;
    }
}
