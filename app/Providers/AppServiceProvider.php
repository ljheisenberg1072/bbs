<?php

namespace App\Providers;

use App\Models\Link;
use App\Models\Reply;
use App\Models\Topic;
use App\Observers\LinkObserver;
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
        if (app()->isLocal()) {
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Topic::observe(TopicObserver::class);
        Reply::observe(ReplyObserver::class);
        Link::observe(LinkObserver::class);
        \Illuminate\Pagination\Paginator::useBootstrap();
    }
}
