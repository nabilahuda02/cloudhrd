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

    // // user
    // $user = new User();
    // $user->unit_id = 1;
    // $user->email = 'user@intranet.boxedge.com';
    // $user->password = Hash::make('user');
    // $user->email_password = 'd1OaddZE';
    // $user->verified = 1;
    // $user->save();

    // // module owner
    // $mo = new User();
    // $mo->unit_id = 1;
    // $mo->email = 'mo@intranet.boxedge.com';
    // $mo->password = Hash::make('mo');
    // $mo->email_password = 'VneS8NNV';
    // $mo->verified = 1;
    // $mo->save();

    // // unit-head
    // $uh = new User();
    // $uh->unit_id = 1;
    // $uh->email = 'uh@intranet.boxedge.com';
    // $uh->email_password = 'usse9Yye';
    // $uh->password = Hash::make('uh');
    // $uh->verified = 1;
    // $uh->save();

	}

}