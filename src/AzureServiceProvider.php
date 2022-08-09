<?php

namespace App\Providers;

use App\Models\AzureAD;
use Illuminate\Support\ServiceProvider;

class AzureServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config.php', 'azure_ad');
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'azure-ad',
            function ($app) use ($socialite) {
                return $socialite->buildProvider(
                    AzureAD::class,
                    [
                        'client_id' => config('azure_ad.client_id'),
                        'client_secret' => config('azure_ad.secret'),
                        'redirect' => str_replace('http:', 'https:', request()->root()) .'/auth/signin',
                    ]
                );
            }
        );
    }
}