<?php

// Composer: "fzaninotto/faker": "v1.3.0"
// use Faker\Factory as Faker;

class StatusTableSeeder extends Seeder
{

    public function run()
    {

        DB::table('status')->truncate();

        $status = new Status();
        $status->name = 'Pending';
        $status->save();

        $status = new Status();
        $status->name = 'Verified';
        $status->save();

        $status = new Status();
        $status->name = 'Approved';
        $status->save();

        $status = new Status();
        $status->name = 'Rejected';
        $status->save();

        $status = new Status();
        $status->name = 'Cancelled';
        $status->save();

        $status = new Status();
        $status->name = 'Draft';
        $status->save();

    }

}
