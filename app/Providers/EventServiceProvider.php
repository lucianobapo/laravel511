<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        'App\Events\SomeEvent' => [
//            'App\Listeners\EventListener',
//        ],
//        'App\Events\PodcastWasPurchased' => [
//            'App\Listeners\EmailPurchaseConfirmation',
//        ],
//        'App\Events\AuthLogin' => [
//            'App\Listeners\AuthLoginConfirmation',
//        ],
        'auth.login' => [
            'App\Handlers\Events\AuthLoginEventHandler',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
