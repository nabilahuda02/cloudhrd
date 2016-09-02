<?php

class MedicalClaimsTablesSeeder extends Seeder
{
    public function run()
    {
        DB::table('medical_claim_user_entitlements')->truncate();
        DB::table('medical_claim_types')->truncate();
        DB::table('medical_claims')->truncate();

        $medicalClaimConfigs = [
            [
                'name' => 'Outpatient',
                'entitlement' => 600.00,
                'colors' => '#246e88',
                'display_wall' => true,
            ],
            [
                'name' => 'Dental',
                'entitlement' => 200.00,
                'colors' => '#516e30',
                'display_wall' => true,
            ],
        ];

        foreach ($medicalClaimConfigs as $medicalConfig) {
            $medicalClaim = new MedicalClaim__Type();
            $medicalClaim->name = $medicalConfig['name'];
            $medicalClaim->default_entitlement = $medicalConfig['entitlement'];
            $medicalClaim->colors = $medicalConfig['colors'];
            $medicalClaim->display_wall = $medicalConfig['display_wall'];
            $medicalClaim->save();
        }
    }
}
