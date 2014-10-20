<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UserProfilesTableSeeder extends Seeder {

	public function run()
	{

        DB::table('user_profiles')->truncate();

        $admin = new UserProfile();
        $admin->user_id = 1;
        $admin->first_name = 'admin';
        $admin->user_image = '/images/user.jpg';
        $admin->save();

    }

}