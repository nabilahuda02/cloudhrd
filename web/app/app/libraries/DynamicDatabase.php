<?php

class DynamicDatabase
{
    public static function boot($host)
    {
        $config = Cache::remember('config.' . $host, 10, function () use ($host) {
            return Master__User::where('domain', '=', $host)->first();
        });
        if (!$config) {
            return false;
        } else if ($config->confirmed === 0) {
            return null;
        } else {
            Config::set('database.connections.mysql.database', $config->database);
            try {
                define('STDIN', fopen("php://stdin", "r"));
                $tables = DB::select('show tables from ' . $config->database);
                $admin = User::find(1);
                if ($admin->email == 'user@email') {
                    Artisan::call('db:seed', ['--force' => true]);
                    $admin->password = $config->password;
                    $admin->email = $config->email;
                    $admin->save();
                }
            } catch (Exception $e) {
                Log::error('error creating db', [$e]);
                Config::set('database.connections.mysql.database', 'information_schema');
                DB::select('create database if not exists ' . $config->database);
                shell_exec('PATH=$PATH:/usr/local/bin/ && export PATH && mysqldump -u root cloudhrd_app | mysql -u root ' . $config->database);
                $page = $_SERVER['PHP_SELF'];
                header("Refresh: 0; url=$page");
            }
        }
        return $config;
    }

    public static function flush_cache()
    {
        $host = app()->master_user->domain;
        Cache::forget('config.' . $host);
        app()->master_user = Cache::remember('config.' . $host, 10, function () use ($host) {
            return Master__User::where('domain', '=', $host)->first();
        });
    }

}
