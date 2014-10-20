<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UserModuleTableSeeder extends Seeder {

	public function run()
	{

        DB::table('user_modules')->truncate();

        // admin
        $module = new UserModule();
        $module->user_id = 1;
        $module->module_id = Leave__Main::$moduleId;
        $module->save();

        $module = new UserModule();
        $module->user_id = 1;
        $module->module_id = MedicalClaim__Main::$moduleId;
        $module->save();

        $module = new UserModule();
        $module->user_id = 1;
        $module->module_id = GeneralClaim__Main::$moduleId;
        $module->save();
        
    }

}