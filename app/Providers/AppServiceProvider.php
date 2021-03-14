<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PostType;
use App\Models\Post;
use App\Models\CustomField;
use App\Observers\PostTypeObserver;
use App\Observers\PostObserver;
use App\Observers\CustomFieldObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        PostType::observe(PostTypeObserver::class);
        Post::observe(PostObserver::class);
        CustomField::observe(CustomFieldObserver::class);
    }
}
