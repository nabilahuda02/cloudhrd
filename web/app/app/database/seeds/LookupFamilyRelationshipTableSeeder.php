<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class LookupFamilyRelationshipTableSeeder extends Seeder {

  public function run()
  {

    DB::table('lookup_family_relationships')->truncate();

    $relationship = new Lookup__FamilyRelationship();
    $relationship->name = 'Child';
    $relationship->save();
    $relationship = new Lookup__FamilyRelationship();
    $relationship->name = 'Mother';
    $relationship->save();
    $relationship = new Lookup__FamilyRelationship();
    $relationship->name = 'Father';
    $relationship->save();
    $relationship = new Lookup__FamilyRelationship();
    $relationship->name = 'Husband';
    $relationship->save();
    $relationship = new Lookup__FamilyRelationship();
    $relationship->name = 'Wife';
    $relationship->save();

  }

}