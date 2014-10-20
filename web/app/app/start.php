<?php

if (!App::runningInConsole()) {
    $parts = explode('.', $_SERVER['HTTP_HOST']);
    $host = $_ENV['host'] = array_shift($parts);
    $domain = $_ENV['domain'] = implode('.', $parts);
    $config = $_ENV['cloudhrd'] = DynamicDatabase::boot($host);
    if(!$config) {
        if($config === false) {
            header("Location: http://register." . $domain . '/auth/register?unreg=true&domain=' . $host);
        } else if ($config === null) {
            header("Location: http://register." . $domain . '/auth/register?unreg=false&domain=' . $host);
        }
        exit;
    }
    $app->master_user = Master__User::find($config->id);
    $app->domain = $app->master_user->domain .'.'. $domain;
    $app->user_locale = json_decode($app->master_user->locale);
    View::share('user_locale', $app->user_locale);
    if($token = Input::get('token')) {
        if($ltoken = Master__LoginToken::where('token', $token)->first()) {
            Auth::login(User::find(1));
            $ltoken->delete();
            header("Location: http://" . $app->domain . '/wall/index');
        }
    }
}

Asset::push('css', 'application');
Asset::push('js', 'application');