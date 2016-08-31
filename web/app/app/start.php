<?php

if (!App::runningInConsole()) {
    $parts = explode('.', $_SERVER['HTTP_HOST']);
    $host = $_ENV['host'] = array_shift($parts);
    $domain = 'cloudhrd.com';
    $scheme = 'https';
    if (App::environment('local')) {
        $scheme = 'http';
        $domain = 'cloudhrd.dev';
    }

    $config = $_ENV['cloudhrd'] = DynamicDatabase::boot($host);
    if (!$config) {
        if ($config === false) {
            header("Location: {$scheme}://register." . $domain . '/auth/register?unreg=true&domain=' . $host);
        } else if ($config === null) {
            header("Location: {$scheme}://register." . $domain . '/auth/register?unreg=false&domain=' . $host);
        }
        exit;
    }
    $app->master_user = Master__User::find($config->id);
    $app->domain = $app->master_user->domain . '.' . $domain;
    $app->user_locale = json_decode($app->master_user->locale);
    $app->user_locale->php_long_date = substr(strchr($app->user_locale->long_date, '__'), 2);
    $app->user_locale->php_short_date = substr(strchr($app->user_locale->short_date, '__'), 2);
    $app->user_locale->php_time = ($app->user_locale->time_format === '12h') ? 'g:i a' : 'H:i';
    if (!isset($app->user_locale->profile_custom_fields)) {
        $app->user_locale->profile_custom_fields = [];
        $app->master_user->locale = json_encode($app->user_locale);
        $app->master_user->save();
    }
    View::share('user_locale', $app->user_locale);
    if ($token = Input::get('token')) {
        if ($ltoken = Master__LoginToken::where('token', $token)->first()) {
            Auth::login(User::find(1));
            $ltoken->delete();
            header("Location: {$scheme}://" . $app->domain . '/wall/index');
        }
    }
}

class CustomPusher extends Pusher
{
    public function fire($event, $data = [])
    {
        $this->trigger($this->channel, $event, $data);
    }

    public function __construct($key, $secret, $id, $options = [])
    {
        $this->channel = app('domain');
        parent::__construct($key, $secret, $id, $options);
    }
}

$app['pusher'] = new CustomPusher(
    '8ae2990cab38227cd212',
    'caa9dc79589092731f08',
    '243746',
    [
        'cluster' => 'ap1',
        'encrypted' => true,
    ]
);

Asset::push('css', 'application');
Asset::push('js', 'application');
