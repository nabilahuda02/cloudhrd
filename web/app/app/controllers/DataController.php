<?php

class DataController extends BaseController
{
    public function getLeaves()
    {
        /**
         * Status, Reference Number, Type, Duration, Action
         */
        $leaves = DB::table('leaves')->select(['leaves.id', 'status.name as "status_name"', 'ref', 'leaves.created_at', 'leave_types.name', 'total'])
            ->where('user_id', Auth::user()->id)
            ->orderBy('leaves.created_at', 'desc')
            ->join('status', 'status.id', '=', 'leaves.status_id')
            ->join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id');
        return Datatables::of($leaves)
            ->add_column('action', "{{View::make('leaves.actions-table', compact('id'))->render()}}")
            ->edit_column('created_at', function ($data) {
                return Helper::timestamp($data->created_at);
            })
            ->remove_column('id')
            ->make();
    }

    public function getOtherLeaves()
    {
        /**
         * User, Status, Reference Number, Type, Duration, Action
         */
        $downline = Auth::user()->getDownline(Leave__Main::$moduleId);
        if (count($downline) > 0) {
            $leaves = DB::table('leaves')->select(['leaves.id', 'status.name as "status_name"', 'user_profiles.first_name', 'leaves.created_at', 'ref', 'leave_types.name', 'total'])
                ->whereIn('leaves.user_id', $downline)
                ->orderBy('leaves.created_at', 'desc')
                ->join('users', 'users.id', '=', 'leaves.user_id')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                ->join('status', 'status.id', '=', 'leaves.status_id')
                ->join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id');
        } else {
            $leaves = Leave__Main::whereNull('status_id')->select(['leaves.id', 'status_id', 'user_id', 'ref', 'leave_type_id', 'total']);
        }
        return Datatables::of($leaves)
            ->add_column('action', "{{View::make('leaves.actions-table', compact('id'))->render()}}")
            ->edit_column('created_at', function ($data) {
                return Helper::timestamp($data->created_at);
            })
            ->remove_column('id')
            ->make();
    }

    public function getMedicalClaims()
    {
        /**
         * Status Clinic  Reference No  Claim Type  Amount  Action
         */
        $medical_claims = DB::table('medical_claims')->select(
            ['medical_claims.id',
                'status.name as "status_name"',
                'medical_claims.created_at',
                'ref',
                'medical_claim_types.name',
                'total'])
            ->where('user_id', Auth::user()->id)
            ->orderBy('medical_claims.created_at', 'desc')
            ->join('status', 'status.id', '=', 'medical_claims.status_id')
            ->join('medical_claim_types', 'medical_claim_types.id', '=', 'medical_claims.medical_claim_type_id');
        return Datatables::of($medical_claims)
            ->add_column('action', "{{View::make('medicals.actions-table', compact('id'))->render()}}")
            ->edit_column('total', function ($data) {
                return Helper::currency_format($data->total);
            })
            ->edit_column('created_at', function ($data) {
                return Helper::timestamp($data->created_at);
            })
            ->remove_column('id')
            ->make();
    }

    public function getUnpaidMedicalClaims($payrollUserId, $userId)
    {
        /**
         * Status Clinic  Reference No  Claim Type  Amount  Action
         */
        $medical_claims = DB::table('medical_claims')->select(
            ['medical_claims.id',
                'status.name as "status_name"',
                'medical_claims.created_at',
                'ref',
                'medical_claim_types.name',
                'total'])
            ->where('medical_claims.user_id', $userId)
            ->where('medical_claims.is_paid', false)
            ->where('medical_claims.status_id', 3)
            ->orderBy('medical_claims.created_at', 'desc')
            ->join('status', 'status.id', '=', 'medical_claims.status_id')
            ->join('medical_claim_types', 'medical_claim_types.id', '=', 'medical_claims.medical_claim_type_id');
        return Datatables::of($medical_claims)
            ->add_column('action', function ($medical_claims) use ($payrollUserId) {
                return '<a href="' . action('AdminPayrollController@getApplyMedicalClaim', [$payrollUserId, $medical_claims->id]) . '" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></a>';
            })
            ->edit_column('total', function ($data) {
                return Helper::currency_format($data->total);
            })
            ->edit_column('created_at', function ($data) {
                return Helper::timestamp($data->created_at);
            })
            ->remove_column('id')
            ->make();
    }

    public function getOtherMedicalClaims()
    {
        /**
         * User Status  Clinic  Reference No  Claim Type  Amount  Action
         */
        $downline = Auth::user()->getDownline(MedicalClaim__Main::$moduleId);
        if (count($downline) > 0) {
            $medical_claims = DB::table('medical_claims')->select(
                ['medical_claims.id',
                    'status.name as "status_name"',
                    'medical_claims.created_at',
                    'user_profiles.first_name',
                    'ref',
                    'medical_claim_types.name',
                    'total'])
                ->whereIn('medical_claims.user_id', $downline)
                ->orderBy('medical_claims.created_at', 'desc')
                ->join('users', 'users.id', '=', 'medical_claims.user_id')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                ->join('status', 'status.id', '=', 'medical_claims.status_id')
                ->join('medical_claim_types', 'medical_claim_types.id', '=', 'medical_claims.medical_claim_type_id');
        } else {
            $medical_claims = MedicalClaim__Main::whereNull('status_id')->select(
                ['medical_claims.id',
                    'status_id',
                    'user_id',
                    'ref',
                    'medical_claim_type_id',
                    'total']);
        }
        return Datatables::of($medical_claims)
            ->add_column('action', "{{View::make('medicals.actions-table', compact('id'))->render()}}")
            ->edit_column('total', function ($data) {
                return Helper::currency_format($data->total);
            })
            ->edit_column('created_at', function ($data) {
                return Helper::timestamp($data->created_at);
            })
            ->remove_column('id')
            ->make();
    }

    public function getGeneralClaims()
    {
        /**
         * Status  Reference No  Request Date  Claim Description  Amount  Action
         */
        $general_claims = DB::table('general_claims')->select(
            ['general_claims.id',
                'status.name as "status_name"',
                'ref',
                DB::Raw('date(general_claims.created_at) as date'),
                'title',
                'value'])
            ->orderBy('general_claims.created_at', 'desc')
            ->where('user_id', Auth::user()->id)
            ->join('status', 'status.id', '=', 'general_claims.status_id');
        return Datatables::of($general_claims)
            ->add_column('action', "{{View::make('generalclaims.actions-table', compact('id'))->render()}}")
            ->edit_column('value', function ($data) {
                return Helper::currency_format($data->value);
            })
            ->remove_column('id')
            ->make();
    }

    public function getUnpaidGeneralClaims($payrollUserId, $userId)
    {
        /**
         * Status  Reference No  Request Date  Claim Description  Amount  Action
         */

        $general_claims = DB::table('general_claims')->select(
            ['general_claims.id',
                'status.name as "status_name"',
                'ref',
                DB::Raw('date(general_claims.created_at) as date'),
                'title',
                'value'])
            ->orderBy('general_claims.created_at', 'desc')
            ->where('general_claims.user_id', $userId)
            ->where('general_claims.is_paid', false)
            ->where('general_claims.status_id', 3)
            ->join('status', 'status.id', '=', 'general_claims.status_id');
        return Datatables::of($general_claims)
            ->add_column('action', function ($general_claims) use ($payrollUserId) {
                return '<a href="' . action('AdminPayrollController@getApplyGeneralClaim', [$payrollUserId, $general_claims->id]) . '" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></a>';
            })
            ->edit_column('value', function ($data) {
                return Helper::currency_format($data->value);
            })
            ->remove_column('id')
            ->make();
    }

    public function getOtherGeneralClaims()
    {
        /**
         * User Status  Reference No  Request Date  Claim Description  Amount  Action
         */
        $downline = Auth::user()->getDownline(GeneralClaim__Main::$moduleId);
        if (count($downline) > 0) {
            $general_claims = DB::table('general_claims')->select(
                ['general_claims.id',
                    'status.name as "status_name"',
                    'user_profiles.first_name',
                    'ref',
                    DB::Raw('date(general_claims.created_at) as date'),
                    'title',
                    'value'])
                ->orderBy('general_claims.created_at', 'desc')
                ->whereIn('general_claims.user_id', $downline)
                ->join('users', 'users.id', '=', 'general_claims.user_id')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                ->join('status', 'status.id', '=', 'general_claims.status_id');
        } else {
            $general_claims = GeneralClaim__Main::whereNull('status_id')->select(
                ['general_claims.id',
                    'status.name as "status_name"',
                    'user_id',
                    'ref',
                    'created_at',
                    'title',
                    'value']);
        }
        return Datatables::of($general_claims)
            ->add_column('action', "{{View::make('generalclaims.actions-table', compact('id'))->render()}}")
            ->edit_column('value', function ($data) {
                return Helper::currency_format($data->value);
            })
            ->remove_column('id')
            ->make();
    }

    public function getRoomBookings()
    {
        /**
         * Status Reference No  Room  Slots Action
         */
        $room_bookings = DB::table('room_bookings')->select(
            ['room_bookings.id',
                'status.name as "status_name"',
                'ref',
                'room_bookings.booking_date',
                'room_booking_rooms.name',
                DB::Raw('group_concat(concat("(", TIME_FORMAT(lookup_timing_slots.start, "%l:%i %p"), " - ", TIME_FORMAT(lookup_timing_slots.end, "%l:%i %p"), ") ", lookup_timing_slots.name) SEPARATOR "<br/> ") as slots')])
            ->where('user_id', Auth::user()->id)
            ->orderBy('room_bookings.created_at', 'desc')
            ->join('room_booking_timing_slots', 'room_booking_timing_slots.room_booking_id', '=', 'room_bookings.id')
            ->join('lookup_timing_slots', 'lookup_timing_slots.id', '=', 'room_booking_timing_slots.lookup_timing_slot_id')
            ->join('room_booking_rooms', 'room_booking_rooms.id', '=', 'room_bookings.room_booking_room_id')
            ->join('status', 'status.id', '=', 'room_bookings.status_id')
            ->groupBy('room_bookings.id');
        return Datatables::of($room_bookings)
            ->add_column('action', "{{View::make('booking.actions-table', compact('id'))->render()}}")
            ->remove_column('id')
            ->make();
    }

    public function getOtherRoomBookings()
    {
        /**
         * User Status  Reference No  Room  Slots Action
         */
        $downline = Auth::user()->getDownline(RoomBooking__Main::$moduleId);
        if (count($downline) > 0) {
            $room_bookings = DB::table('room_bookings')->select(
                ['room_bookings.id',
                    'status.name as "status_name"',
                    'ref',
                    'user_profiles.first_name',
                    'room_bookings.booking_date',
                    'room_booking_rooms.name',
                    DB::Raw('group_concat(concat("(", TIME_FORMAT(lookup_timing_slots.start, "%l:%i %p"), " - ", TIME_FORMAT(lookup_timing_slots.end, "%l:%i %p"), ") ", lookup_timing_slots.name) SEPARATOR "<br/> ") as slots')])
                ->whereIn('room_bookings.user_id', $downline)
                ->orderBy('room_bookings.created_at', 'desc')
                ->join('room_booking_timing_slots', 'room_booking_timing_slots.room_booking_id', '=', 'room_bookings.id')
                ->join('lookup_timing_slots', 'lookup_timing_slots.id', '=', 'room_booking_timing_slots.lookup_timing_slot_id')
                ->join('users', 'users.id', '=', 'room_bookings.user_id')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                ->join('room_booking_rooms', 'room_booking_rooms.id', '=', 'room_bookings.room_booking_room_id')
                ->join('status', 'status.id', '=', 'room_bookings.status_id')
                ->groupBy('room_bookings.id');
        } else {
            $room_bookings = RoomBooking__Main::whereNull('status_id')->select(
                ['id',
                    'user_id',
                    'status_id',
                    'ref',
                    'room_booking_room_id',
                    'created_at']);
        }
        return Datatables::of($room_bookings)
            ->add_column('action', "{{View::make('booking.actions-table', compact('id'))->render()}}")
            ->remove_column('id')
            ->make();
    }

    public function getAudits()
    {
        $audits = Audit::select([
            'audits.id',
            'data',
            'audits.auditable_type',
            'audits.created_at',
            'audits.type_mask',
            'audits.ref',
            'audits.type',
            'user_profiles.first_name',
        ])
            ->join('users', 'users.id', '=', 'audits.user_id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->orderBy('audits.updated_at', 'desc');
        return Datatables::of($audits)
            ->add_column('action', "<button class='btn btn-primary btn-xs viewauditdetails' data-auditdata='{{json_encode(\$data)}}'><i class='fa fa-eye'></i></button>")
            ->remove_column('id')
            ->remove_column('data')
            ->remove_column('auditable_type')
            ->make();
    }

    public function getPayrolls()
    {
        $payroll = Payroll__Main::select([
            'payrolls.id',
            'payrolls.name',
            'payrolls.total',
            'status.name as status_name',
        ])
            ->join('status', 'status.id', '=', 'payrolls.status_id');

        $isAdmin = true;
        if (!Auth::user()->administers(5)) {
            $payroll->where('status_id', 7);
            $isAdmin = false;
        }

        return Datatables::of($payroll)
            ->add_column('action', function ($payroll) use ($isAdmin) {
                $actions = '<a href="' . action('AdminPayrollController@getDetails', $payroll->id) . '" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>';
                return $actions;
            })
            ->remove_column('id')
            ->make();
    }

    public function getMyPayrolls()
    {
        $payroll = Payroll__Main::select([
            'payrolls.id',
            'payrolls.name',
            'payrolls.total',
            'status.name as status_name',
        ])
            ->join('status', 'status.id', '=', 'payrolls.status_id');
        $payroll->where('status_id', 7);

        return Datatables::of($payroll)
            ->add_column('action', function ($payroll) {
                $actions = '<a href="' . action('PayrollsController@show', $payroll->id) . '" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>';
                return $actions;
            })
            ->remove_column('id')
            ->make();
    }

    public function getPayroll($id)
    {

        $payrolls = Payroll__User::where('payroll_id', $id)->select([
            'payroll_user.id',
            'user_units.name',
            DB::Raw('concat(user_profiles.first_name, " ", user_profiles.last_name)'),
            'user_profiles.bank_name',
            'user_profiles.bank_account',
            'payroll_user.total',
        ])
            ->join('users', 'users.id', '=', 'payroll_user.user_id')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->join('user_units', 'user_units.id', '=', 'users.unit_id')
        ;

        return Datatables::of($payrolls)
            ->add_column('action', function ($payroll) {
                $actions = '<a href="' . action('AdminPayrollController@getUserDetails', $payroll->id) . '" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>';
                return $actions;
            })
            ->remove_column('id')
            ->make();
    }

    public function getPayrollEpf($id)
    {

        $payrolls = Payroll__User::where('payroll_id', $id)->select([
            'payroll_user.id',
            'user_units.name',
            DB::Raw('concat(user_profiles.first_name, " ", user_profiles.last_name)'),
            'user_profiles.kwsp_account',
            'epf_contributions.employee_contribution',
            'epf_contributions.employer_contribution',
            DB::Raw('epf_contributions.employee_contribution + epf_contributions.employer_contribution as total'),
        ])
            ->join('users', 'users.id', '=', 'payroll_user.user_id')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->join('user_units', 'user_units.id', '=', 'users.unit_id')
            ->join('epf_contributions', 'epf_contributions.payroll_user_id', '=', 'payroll_user.id')
        ;

        return Datatables::of($payrolls)
            ->remove_column('id')
            ->make();
    }

    public function getPayrollSocso($id)
    {

        $payrolls = Payroll__User::where('payroll_id', $id)->select([
            'payroll_user.id',
            'user_units.name',
            DB::Raw('concat(user_profiles.first_name, " ", user_profiles.last_name)'),
            'user_profiles.socso_account',
            'socso_contributions.employee_contribution',
            'socso_contributions.employer_contribution',
            DB::Raw('socso_contributions.employee_contribution + socso_contributions.employer_contribution as total'),
        ])
            ->join('users', 'users.id', '=', 'payroll_user.user_id')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->join('user_units', 'user_units.id', '=', 'users.unit_id')
            ->join('socso_contributions', 'socso_contributions.payroll_user_id', '=', 'payroll_user.id')
        ;

        return Datatables::of($payrolls)
            ->remove_column('id')
            ->make();
    }

    public function getPayrollPcb($id)
    {

        $payrolls = Payroll__User::where('payroll_id', $id)->select([
            'payroll_user.id',
            'user_units.name',
            DB::Raw('concat(user_profiles.first_name, " ", user_profiles.last_name)'),
            'user_profiles.lhdn_account',
            'pcb_contributions.employee_contribution',
        ])
            ->join('users', 'users.id', '=', 'payroll_user.user_id')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->join('user_units', 'user_units.id', '=', 'users.unit_id')
            ->join('pcb_contributions', 'pcb_contributions.payroll_user_id', '=', 'payroll_user.id')
        ;

        return Datatables::of($payrolls)
            ->remove_column('id')
            ->make();
    }

    public function getChangeRequests()
    {
        /**
         * Status, Reference Number, Type, Duration, Action
         */
        $change_requests = DB::table('change_requests')->select(['change_requests.id', 'status.name as "status_name"', 'ref', 'change_requests.created_at'])
            ->where('user_id', Auth::user()->id)
            ->orderBy('change_requests.created_at', 'desc')
            ->join('status', 'status.id', '=', 'change_requests.status_id');
        return Datatables::of($change_requests)
            ->add_column('action', "{{View::make('changerequests.actions-table', compact('id'))->render()}}")
            ->edit_column('created_at', function ($data) {
                return Helper::timestamp($data->created_at);
            })
            ->remove_column('id')
            ->make();
    }

    public function getOtherChangeRequests()
    {
        /**
         * User, Status, Reference Number, Type, Duration, Action
         */
        $downline = Auth::user()->getDownline(Leave__Main::$moduleId);
        if (count($downline) > 0) {
            $change_requests = DB::table('change_requests')->select(['change_requests.id', 'status.name as "status_name"', 'user_profiles.first_name', 'change_requests.created_at', 'ref'])
                ->whereIn('change_requests.user_id', $downline)
                ->orderBy('change_requests.created_at', 'desc')
                ->join('users', 'users.id', '=', 'change_requests.user_id')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                ->join('status', 'status.id', '=', 'change_requests.status_id');
        } else {
            $change_requests = Leave__Main::whereNull('status_id')->select(['change_requests.id', 'status_id', 'user_id', 'ref']);
        }
        return Datatables::of($change_requests)
            ->add_column('action', "{{View::make('changerequests.actions-table', compact('id'))->render()}}")
            ->edit_column('created_at', function ($data) {
                return Helper::timestamp($data->created_at);
            })
            ->remove_column('id')
            ->make();
    }

}
