<?php

namespace DoubleC\LaravelShopify\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppThemeInfoFetched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     * @param array|null $themeInfo
     */
    public function __construct(private ?array $themeInfo)
    {
    }

    /**
     * Get current theme info
     * @return array|null
     */
    public function theme(): ?array
    {
        return $this->themeInfo;
    }
}
