<?php

namespace App\Providers;

use App\Enums\UserLevel;
use App\Models\WorkTimeType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Carbon::setLocale('pl');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->level == UserLevel::ADMIN;
        });

        Blade::if('adminAndWorker', function () {
            return auth()->check() && (auth()->user()->level == UserLevel::ADMIN || auth()->user()->level == UserLevel::WORKER);
        });

        view()->composer(['layouts.app'], function ($view) {
            $view
                ->with('modalWorkTimeTypes', WorkTimeType::orderBy('order', 'asc')->get())
                ->with('modalProjects', auth()->user() ? auth()->user()->getAllUserProjects() : collect())
                ->with('unreadCommentsCount', auth()->user() ? auth()->user()->unreadCommentsCount() : 0);
        });
    }
}
