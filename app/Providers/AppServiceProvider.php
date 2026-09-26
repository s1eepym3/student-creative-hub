<?php

namespace App\Providers;

use App\Events\ViewRecorded;
use App\Listeners\LogViewRecord;
use App\Models\Mahasiswa;
use App\Policies\MahasiswaPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();

        // Register the Portfolio Analytics event listener
        Event::listen(ViewRecorded::class, LogViewRecord::class);

        // Register the Mahasiswa Policy for PDF authorization
        Gate::policy(Mahasiswa::class, MahasiswaPolicy::class);
    }
}
