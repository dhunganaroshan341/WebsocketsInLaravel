<?php

namespace App\Listeners;

use App\Events\HelloWorldEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AnotherHelloListener
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
    public function handle(HelloWorldEvent $event)
    {
        Log::info('Another Listener Executed');
    }
}
