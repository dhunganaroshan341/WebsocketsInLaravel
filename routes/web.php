<?php

use App\Events\HelloWorldEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Events\MessageSent;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {

    Log::info('Route was hit');

    event(new HelloWorldEvent());

    return 'Done!';
});


Route::get('/send', function (Request $request) {

    event(new MessageSent(
        $request->user ?? 'Anonymous',
        $request->message ?? 'Hello World'
    ));

    return 'Broadcast Sent!';
});
