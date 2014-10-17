<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {

	public function run()
	{

        DB::table('users')->truncate();

        // admin
        $admin = new User();
        $admin->unit_id = 1;
        $admin->email = 'admin@intranet.boxedge.com';
        $admin->password = Hash::make('admin');
        $admin->verified = 1;
        $admin->is_admin = true;
        $admin->save();
	}

}