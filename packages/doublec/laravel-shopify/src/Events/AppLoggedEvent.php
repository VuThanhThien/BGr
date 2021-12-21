<?php

namespace DoubleC\LaravelShopify\Events;

use DoubleC\LaravelShopify\Models\Shop;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppLoggedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * When app logged
     * @param Shop $shop
     */
    public function __construct(private Shop $shop)
    {
    }

    /**
     * Get shop
     * @return Shop
     */
    public function shop(): Shop
    {
        return $this->shop;
    }
}
