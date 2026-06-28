<?php

namespace App\Listeners;

use App\Events\HelloWorldEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;


class HelloWorldListener
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

    public function handle(HelloWorldEvent $event): void
    {
        Log::info('HelloWorldListener executed!');
    }
}
