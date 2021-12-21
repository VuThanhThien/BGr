<?php

namespace App\Providers;
use App\Policies\FilePolicy;
use App\Policies\AffiliatePolicy;
use App\Policies\PaymentMethodPolicy;
use App\Policies\ConversionPolicy;
use App\Policies\ShopSettingPolicy;
use App\Policies\AffiliateCouponPolicy;
use App\Policies\GroupPolicy;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Affiliate;
use App\Models\Conversion;
use App\Models\ShopSetting;
use App\Models\AffiliateCoupon;
use App\Models\Group;
use App\Models\PaymentMethod;
use App\Models\File;
use App\Models\Category;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Affiliate::class=>AffiliatePolicy::class,
        Conversion::class=>ConversionPolicy::class,
        ShopSetting::class=>ShopSettingPolicy::class,
        AffiliateCoupon::class=>AffiliateCouponPolicy::class,
        Group::class=>GroupPolicy::class,
        PaymentMethod::class=>PaymentMethodPolicy::class,
        File::class=>FilePolicy::class,
        Category::class => CategoryPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
