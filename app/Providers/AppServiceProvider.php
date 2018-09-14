<?php

namespace App\Providers;

use App\Contracts\ServicesContract;
use App\Http\Controllers\ModelTestController;
use App\ModelTest;
use App\Services\ModelTestService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(ModelTestController::class)
                    ->needs(ServicesContract::class)
                    ->give(function () {
                        return new ModelTestService(new ModelTest);
                    });
    }
}
