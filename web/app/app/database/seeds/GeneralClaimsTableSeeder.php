<?php

// Composer: "fzaninotto/faker": "v1.3.0"
// use Faker\Factory as Faker;

class GeneralClaimsTableSeeder extends Seeder {

	public function run()
	{

    DB::table('general_claims')->truncate();
    DB::table('general_claim_types')->truncate();
    DB::table('general_claim_entries')->truncate();

    // $travel = new GeneralClaim__Type();
    // $travel->name = 'Travel';
    // $travel->unit_price = '0.70';
    // $travel->unit = 'KM';
    // $travel->save();

    // $parking = new GeneralClaim__Type();
    // $parking->name = 'Parking';
    // $parking->unit_price = '3.00';
    // $parking->unit = 'Day';
    // $parking->save();

    // $office = new GeneralClaim__Type();
    // $office->name = 'Office Supplies';
    // $office->save();

    // $entertainment = new GeneralClaim__Type();
    // $entertainment->name = 'Entertainment';
    // $entertainment->save();

  }

}