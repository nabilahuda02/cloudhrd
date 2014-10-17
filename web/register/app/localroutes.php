<?php

/**
 * This file is only loaded under the local environment
 * useful for debugging or testing out new features
 * before moving it to the production environment
 */


Route::get('test', function(){
    return Config::get('subscriptions');
});


Route::get('/reset', function(){
    Artisan::call('app:reset');
    return Redirect::to('/auth/login');
});

