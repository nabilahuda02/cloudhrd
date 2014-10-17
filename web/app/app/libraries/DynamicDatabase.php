<?php

class DynamicDatabase
{
    public static function boot($host)
    {
        $config = Cache::remember('config.' . $host, 10, function() use ($host) {
            return DB::connection('mysql_register')->table('users')->where('domain', '=', $host)->first();
        });
        if(!$config) {
            return false;
        } else if ($config->confirmed === 0) {
            return null;
        } else {
            Config::set('database.connections.mysql.database', $config->database);
            try {
                $tables = DB::select('show tables from ' . $config->database);
                if(count(DB::table('users')->get()) === 0) {
                    Artisan::call('db:seed');
                }
                $admin = User::find(1);
                $admin->password = $config->password;
                $admin->email = $config->email;
                $admin->save();
            } catch (Exception $e) {
                Config::set('database.connections.mysql.database', 'test');
                DB::select('create database if not exists ' . $config->database);
                $cloudhrd_tables = DB::select('show tables from cloudhrd_app');
                foreach ($cloudhrd_tables as $table) {
                    DB::select('create table ' . $config->database . '.' . $table->Tables_in_cloudhrd_app . ' like cloudhrd_app.' . $table->Tables_in_cloudhrd_app);
                }
                $page = $_SERVER['PHP_SELF'];
                header("Refresh: 0; url=$page");
            }
        }
        return $config;
    }
}