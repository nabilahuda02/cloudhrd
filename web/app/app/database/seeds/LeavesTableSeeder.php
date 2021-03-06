<?php

// Composer: "fzaninotto/faker": "v1.3.0"

class LeavesTableSeeder extends Seeder
{

    public function run()
    {

        DB::table('leave_types')->truncate();
        DB::table('leave_user_entitlements')->truncate();
        DB::table('leaves')->truncate();
        DB::table('leave_dates')->truncate();

        $leaveConfigs = [
            [
                'name' => 'Annual Leave',
                'default_entitlement' => 21,
                'future' => true,
                'past' => false,
                'colors' => '#9f661c',
                'display_calendar' => true,
                'display_wall' => true,
            ],
            [
                'name' => 'Medical Leave',
                'default_entitlement' => 30,
                'future' => false,
                'past' => true,
                'colors' => '#5b4a85',
                'display_calendar' => false,
                'display_wall' => true,
            ],
        ];

        foreach ($leaveConfigs as $leaveConfig) {
            $leave = new Leave__Type();
            $leave->name = $leaveConfig['name'];
            $leave->default_entitlement = $leaveConfig['default_entitlement'];
            $leave->future = $leaveConfig['future'];
            $leave->past = $leaveConfig['past'];
            $leave->display_calendar = $leaveConfig['display_calendar'];
            $leave->display_wall = $leaveConfig['display_wall'];
            $leave->colors = $leaveConfig['colors'];
            $leave->save();

        }
    }
}
