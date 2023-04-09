<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        /// Monitoring Cumulative Query on the databse
        // DB::whenQueryingForLongerThan(500, function (
        //     Connection $connection,
        //     QueryExecuted $event
        // ) {
        //     // Notify development team...
        // });

        ///This method can be useful for logging queries or debugging
        // DB::listen(function (QueryExecuted $query) {
        //     // $query->sql;
        //     // $query->bindings;
        //     // $query->time;
        // });
    }
}
