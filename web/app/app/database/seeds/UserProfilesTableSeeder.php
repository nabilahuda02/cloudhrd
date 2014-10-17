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

    // $user = new UserProfile();
    // $user->user_id = 2;
    // $user->first_name = 'user';
    // $user->user_image = 'http://api.randomuser.me/portraits/men/2.jpg';
    // $user->save();

    // $user = new UserProfile();
    // $user->user_id = 3;
    // $user->first_name = 'module owner';
    // $user->user_image = 'http://api.randomuser.me/portraits/men/3.jpg';
    // $user->save();

    // $user = new UserProfile();
    // $user->user_id = 4;
    // $user->first_name = 'unit head';
    // $user->user_image = 'http://api.randomuser.me/portraits/men/4.jpg';
    // $user->save();

	}

}