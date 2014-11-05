<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class ModuleTableSeeder extends Seeder {

	public function run()
	{

        DB::table('modules')->truncate();

        $module = new Module();
        $module->id = Leave__Main::$moduleId;
        $module->name = 'Leave';
        $module->approver = -1;
        $module->verifier = -2;
        $module->save();

        $module = new Module();
        $module->id = MedicalClaim__Main::$moduleId;
        $module->name = 'Medical Claims';
        $module->approver = -1;
        $module->verifier = -2;
        $module->save();

        $module = new Module();
        $module->id = GeneralClaim__Main::$moduleId;
        $module->name = 'General Claims';
        $module->approver = -1;
        $module->verifier = -2;
        $module->save();

        $module = new Module();
        $module->id = Task__Main::$moduleId;
        $module->name = 'Tasks';
        $module->approver = -1;
        $module->verifier = -2;
        $module->has_config = 0;
        $module->save();

    }

}