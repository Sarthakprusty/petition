<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\GeneratePdfEventAck::class => [
            \App\Listeners\GeneratePdfListenerAck::class,
        ],
        \App\Events\SendEmailEventAck::class => [
            \App\Listeners\SendEmailListenerAck::class,
        ],
        \App\Events\GeneratePdfEventFwd::class => [
            \App\Listeners\GeneratePdfListenerFwd::class,
        ],
        \App\Events\SendEmailEventFwd::class => [
            \App\Listeners\SendEmailListenerFwd::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
