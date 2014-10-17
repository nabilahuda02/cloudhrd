<?php

class MedicalClaimsTablesSeeder extends Seeder {
	public function run()
	{
    DB::table('medical_claim_user_entitlements')->truncate();
    DB::table('medical_claim_types')->truncate();
    DB::table('medical_claims')->truncate();

    $medicalClaimConfigs = [
      [
        'name' => 'Outpatient',
        'entitlement' => 600,
        'colors' => '#246e88,#3bafda',
        'display_wall' => true
      ],
      [
        'name' => 'Dental',
        'entitlement' => 200,
        'colors' => '#516e30,#8cc152',
        'display_wall' => true
      ]
    ];

    foreach ($medicalClaimConfigs as $medicalConfig) {
      $medicalClaim = new MedicalClaim__Type();
      $medicalClaim->name = $medicalConfig['name'];
      $medicalClaim->default_entitlement = $medicalConfig['entitlement'];
      $medicalClaim->colors = $medicalConfig['colors'];
      $medicalClaim->display_wall = $medicalConfig['display_wall'];
      $medicalClaim->save();
    }

    // $clinic = new MedicalClaim__PanelClinic();
    // $clinic->name = 'Klinik Sejahtera';
    // $clinic->save();

    // $clinic2 = new MedicalClaim__PanelClinic();
    // $clinic2->name = 'Klinik Sihat';
    // $clinic2->save();
  }
}