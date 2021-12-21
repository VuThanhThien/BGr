<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::domain(config('endpoint.api_domain'))
                ->prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::domain(config('endpoint.api_domain'))
                ->prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/affiliate_api.php'));

            Route::domain('app.'.config('endpoint.main_domain'))
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/affiliate.php'));

            
            Route::domain(config('endpoint.api_domain'))
                ->prefix('webhook')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/webhook.php'));

            Route::domain(config('endpoint.api_domain'))
                ->prefix('ses')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/ses.php'));

        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(500)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
