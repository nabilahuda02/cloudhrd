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
                $tables = DB::select('show tables from ' . $config->database);
                if (count(DB::table('users')->get()) === 0) {
                    Artisan::call('db:seed');
                }
                $admin = User::find(1);
                if ($admin->email != $config->email) {
                    $admin->password = $config->password;
                    $admin->email = $config->email;
                    $admin->save();
                }
            } catch (Exception $e) {
                Log::error('error creating db', [$e]);
                Config::set('database.connections.mysql.database', 'information_schema');
                DB::select('create database if not exists ' . $config->database);
                $cloudhrd_tables = DB::select('show tables from cloudhrd_app');
                foreach ($cloudhrd_tables as $table) {
                    DB::select('create table if not exists ' . $config->database . '.' . $table->Tables_in_cloudhrd_app . ' like cloudhrd_app.' . $table->Tables_in_cloudhrd_app);
                }
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
