<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Events\ItemDeleted;
use App\Events\ItemColorDeleted;
use App\Events\ItemImageDeleted;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\DeleteItemAllImages;
use App\Listeners\DeleteItemColorImage;
use App\Listeners\DeleteItemImage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LogSuccessfulLogin::class,
        ],
    ];

    /**
     * イベントとリスナを自動的に検出するか指定
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
