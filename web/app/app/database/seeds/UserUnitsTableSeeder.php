<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UserUnitsTableSeeder extends Seeder {

	public function run()
	{

    DB::table('user_units')->truncate();
    
    UserUnit::create(['id' => 1, 'name' => 'main', 'user_id' => 1]);

	}

}