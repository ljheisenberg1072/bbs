<?php

namespace App\Providers;

use App\Models\Reply;
use App\Models\Topic;
use App\Observers\ReplyObserver;
use App\Observers\TopicObserver;
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
        Topic::observe(TopicObserver::class);
        Reply::observe(ReplyObserver::class);
        \Illuminate\Pagination\Paginator::useBootstrap();
    }
}
