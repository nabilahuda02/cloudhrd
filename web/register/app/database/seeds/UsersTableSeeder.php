<?php

class UsersTableSeeder extends Seeder
{

    public function run()
    {

        User::truncate();
        Subscription::truncate();

        if(app()->env !== 'testing') {
            foreach (DB::select('show databases') as $database) {
                if(stristr($database->Database, 'hrduser_'))
                    DB::select('drop database ' . $database->Database);
            }
        }

        $datas = [
            [
                'name'                  => 'Administrator',
                'username'              => 'admin',
                'domain'                => 'register',
                'email'                 => 'admin@example.com',
                'password'              => 'admin',
                'password_confirmation' => 'admin',
                'organization_unit_id'  => 1,
                'confirmed'             => 1,
            ],
            [
                'name'                  => 'Sales',
                'username'              => 'sales',
                'email'                 => 'sales@my-sands.com',
                'password'              => 'sales',
                'password_confirmation' => 'sales',
                'organization_unit_id'  => 1,
                'confirmed'             => 1,
                'reseller_code'         => User::getResellerCode()
            ],
            [
                'name'                  => 'Sands Consulting',
                'username'              => 'sands',
                'domain'                => 'sands',
                'database'              => User::generateDatabaseName(),
                'email'                 => 'zulfa@my-sands.com',
                'password'              => 'sands',
                'password_confirmation' => 'sands',
                'organization_unit_id'  => 1,
                'confirmed'             => 1,
            ],
        ];

        $roles = [
            1 => [1],
            2 => [6],
            3 => [7],
        ];

        foreach ($datas as $data) {
            $user = User::create($data);
            if(isset($roles[$user->id]))
                $user->roles()->sync($roles[$user->id]);
        }
    }

}
