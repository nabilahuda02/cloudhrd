<?php

// Composer: "fzaninotto/faker": "v1.3.0"
// use Faker\Factory as Faker;

class GeneralClaimsTableSeeder extends Seeder {

    public function run()
    {

        DB::table('general_claims')->truncate();
        DB::table('general_claim_types')->truncate();
        DB::table('general_claim_entries')->truncate();

        $travel = new GeneralClaim__Type();
        $travel->name = 'Travel';
        $travel->unit_price = '1.20';
        $travel->unit = 'Mi';
        $travel->save();

        $office = new GeneralClaim__Type();
        $office->name = 'Office Supplies';
        $office->save();

        $office = new GeneralClaim__Type();
        $office->name = 'Office Upkeep';
        $office->save();

    }

}