<?php

namespace App\Listeners;

use App\Notifications\ClicksNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClickListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if(in_array($event->link->clicks, [1, 5, 10, 100, 100])){
            $event->link->user->notify(new ClicksNotification($event->link));
        }
    }
}
