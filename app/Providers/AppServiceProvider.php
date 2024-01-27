<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());

        Password::defaults(function () {
            $rule = Password::min(8);

            return app()->isProduction()
                ? $rule->letters()
                ->numbers()
                ->symbols()
                ->mixedCase()
                ->uncompromised()
                : $rule;
        });

    }
}
