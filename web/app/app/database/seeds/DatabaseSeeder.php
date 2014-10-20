<?php

class DatabaseSeeder extends Seeder {

    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        Eloquent::unguard();

        DB::select(DB::raw('SET FOREIGN_KEY_CHECKS=0'));

        $this->call('UserUnitsTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('UserProfilesTableSeeder');
        $this->call('StatusTableSeeder');
        $this->call('ModuleTableSeeder');
        $this->call('UserModuleTableSeeder');
        $this->call('LeavesTableSeeder');
        $this->call('MedicalClaimsTablesSeeder');
        $this->call('GeneralClaimsTableSeeder');
        $this->call('LookupFamilyRelationshipTableSeeder');

        DB::table('uploads')->truncate();
        DB::table('audits')->truncate();
        DB::table('share_comments')->truncate();
        DB::table('shares')->truncate();
        DB::table('user_profile_contacts')->truncate();
        DB::table('user_profile_education_histories')->truncate();
        DB::table('user_profile_emergency_contacts')->truncate();
        DB::table('user_profile_employment_histories')->truncate();
        DB::table('user_profile_family_members')->truncate();

        DB::select(DB::raw('SET FOREIGN_KEY_CHECKS=1'));
    }

}
