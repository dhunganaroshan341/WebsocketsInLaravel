<?php

use App\Events\HelloWorldEvent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {

    Log::info('Route was hit');

    event(new HelloWorldEvent());

    return 'Done!';
});
