<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use DoubleC\LaravelShopify\Events\AppInstalledEvent;
use App\Listeners\AppInstalled;
use App\Events\AffiliateRegisteredEvent;
use App\Listeners\AffiliateRegistered;
use App\Events\ApprovedConversionEvent;
use App\Listeners\ApprovedConversion;
use App\Events\NewConversionEvent;
use App\Listeners\NewConversion;
use App\Events\DeniedConversionEvent;
use App\Listeners\DeniedConversion;
use App\Events\ApprovedAffiliateEvent;
use App\Listeners\ApprovedAffiliate;
use App\Events\DeniedAffiliateEvent;
use App\Listeners\DeniedAffiliate;
use App\Events\NewAffiliateEvent;
use App\Listeners\NewAffiliate;
use App\Events\CommissionPayoutEvent;
use App\Listeners\CommissionPayout;
use App\Events\AddAffiliateEvent;
use App\Listeners\AddAffiliate;
use Illuminate\Mail\Events\MessageSending;
use App\Listeners\LogSendingMessage;
use App\Events\BulkEmailEvent;
use App\Listeners\BlukEmail;
use App\Events\CheckAutoTierEvent;
use App\Listeners\CheckAutoTierListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AppInstalledEvent::class => [
            AppInstalled::class,
        ],
        ApprovedConversionEvent::class => [
            ApprovedConversion::class,
        ],
        NewConversionEvent::class => [
            NewConversion::class,
        ],
        DeniedConversionEvent::class => [
            DeniedConversion::class,
        ],
        AffiliateRegisteredEvent::class => [
            AffiliateRegistered::class,
        ],
        ApprovedAffiliateEvent::class => [
            ApprovedAffiliate::class,
        ],
        DeniedAffiliateEvent::class => [
            DeniedAffiliate::class,
        ],
        NewAffiliateEvent::class => [
            NewAffiliate::class,
        ],
        CommissionPayoutEvent::class => [
            CommissionPayout::class,
        ],
        AddAffiliateEvent::class => [
            AddAffiliate::class,
        ],
        MessageSending::class => [
            LogSendingMessage::class,
        ],
        CheckAutoTierEvent::class => [
            CheckAutoTierListener::class
        ]  
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
