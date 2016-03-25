<?php

class PayrollsController extends \BaseController
{

    /**
     * Display a listing of payrolls
     *
     * @return Response
     */
    public function index()
    {
        $downlines = [];
        return View::make('payrolls.index', compact('downlines'));
    }

    /**
     * Show the form for creating a new payroll
     *
     * @return Response
     */
    public function create()
    {
        return View::make('payrolls.create');
    }

    /**
     * Store a newly created payroll in storage.
     *
     * @return Response
     */
    public function store()
    {

        $validator = Validator::make($data = Input::all(), Payroll__Main::$rules);
        if ($validator->fails()) {
            Session::flash('NotifyDanger', 'Error Creating Payroll');
            return Redirect::back()->withErrors($validator)->withInput();
        }
        Session::flash('NotifySuccess', 'Payroll Created Successfully');
        $data['status_id'] = 6;
        $payroll = Payroll__Main::create($data);

        // process salary
        $users = User::with('profile')->whereHas('unit', function ($query) {
            $query->where('is_onpayroll', 1);
        })->get();

        foreach ($users as $user) {
            // create payroll_user
            $payrollUser = $payroll->payrollUsers()->create([
                'user_id' => $user->id,
                'total' => 0,
            ]);

            // Create Pay
            $payrollUser->items()->create([
                'amount' => $user->profile->salary,
                'name' => 'Pay for ' . $payroll->name,
            ]);

            // Deduct KWSP
            $epf = $user->profile->salary * ($user->profile->kwsp_contribution / 100) * -1;
            if ($epf) {
                $payrollUser->items()->create([
                    'amount' => $epf,
                    'name' => "EPF Contribution ({$user->profile->kwsp_contribution}%)",
                ]);
            }

            // Deduct SOCSO
            $socso = $user->profile->socso_contribution * -1;
            if ($socso) {
                $payrollUser->items()->create([
                    'amount' => $socso,
                    'name' => "SOCSO Contribution",
                ]);
            }

            // Deduct PCB
            $pcb = $user->profile->pcb_contribution * -1;
            if ($pcb) {
                $payrollUser->items()->create([
                    'amount' => $pcb,
                    'name' => "PCB Contribution",
                ]);
            }

            $payrollUser->updateTotal();
            $payroll->updateTotal();
        }
        $payroll->setStatus(6);
        return Redirect::route('payrolls.index');
    }

    /**
     * Display the specified payroll.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $payroll = Payroll__Main::findOrFail($id);
        if (!$payroll->canView()) {
            return Redirect::action('payrolls.index');
        }

        return View::make('payrolls.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified payroll.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $payroll = Payroll__Main::find($id);
        if (!$payroll->canEdit()) {
            return Redirect::action('payrolls.index');
        }

        return View::make('payrolls.edit', compact('payroll'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $payroll = Payroll__Main::findOrFail($id);

        $data = Input::all();

        /* update status */
        if (isset($data['_status']) && isset($data['status_id']) && (
            $payroll->canApprove() ||
            $payroll->canReject() ||
            $payroll->canCancel() ||
            $payroll->canVerify())
        ) {
            Session::flash('NotifySuccess', 'Status Updated Successfully');
            $payroll->setStatus($data['status_id']);
            return Redirect::action('payrolls.index');
        }

        if (!$payroll->canEdit()) {
            return Redirect::action('payrolls.index');
        }

        if (!isset($data['user_id']) || (isset($data['user_id']) && !in_array($data['user_id'], Auth::user()->getDownline(Payroll__Main::$moduleId)))) {
            $data['user_id'] = Auth::user()->id;
        }

        $validator = Validator::make($data, Payroll__Main::$rules);

        if ($validator->fails()) {
            Session::flash('NotifyDanger', 'Error Updating Payroll');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Session::flash('NotifySuccess', 'Payroll Updated Successfully');
        $payroll->update($data);

        GeneralPayroll__Entry::where('payroll_id', $payroll->id)->delete();
        foreach ($data['entries'] as $entryJson) {
            $entry = json_decode($entryJson, true);
            $entry['payroll_id'] = $payroll->id;
            $entry['receipt_date'] = Helper::short_date_to_mysql($entry['receipt_date']);
            GeneralPayroll__Entry::create($entry);
        }

        $payroll->audits()->create([
            'ref' => $payroll->ref,
            'type' => 2,
            'data' => $payroll->toArray(),
        ]);

        return Redirect::route('payrolls.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Session::flash('NotifySuccess', 'Payroll Deleted Successfully');
        Payroll__Main::destroy($id);
        return Redirect::route('payrolls.index');
    }

    public function togglePaid($id)
    {
        Session::flash('NotifySuccess', 'Medical Payroll Paid Status Updated Successfully');
        $payroll = Payroll__Main::find($id);
        $payroll->update([
            'is_paid' => !$payroll->is_paid,
        ]);
        return Redirect::route('payrolls.show', $id);
    }

    /**
     * Administration section
     */

    public function getAdminTypes()
    {
        return View::make('payrolls.admin.types');
    }

    public function getAdminReporting()
    {
        return View::make('payrolls.admin.reporting');
    }

    public function postAdminReporting()
    {
        $input = Input::all();
        $tables = [];
        $tabulate = $input['tabulate_by'];

        // dd($input);

        /*

        ["unit"]=> string(0) ""
        ["user_id"]=> string(0) ""
        ["title"]=> string(0) ""
        ["status_id"]=> string(0) ""
        ["create_from_date"]=> string(10) "2014-07-01"
        ["create_to_date"]=> string(0) ""
        ["tabulate_by"]=> string(0) ""
         */

        $general_payrolls = Payroll__Main::join('users', 'users.id', '=', 'general_payrolls.user_id');
        $general_payrolls->select(
            'general_payrolls.id',
            'general_payrolls.ref',
            'general_payrolls.title',
            'general_payrolls.value',
            'general_payrolls.status_id',
            'general_payrolls.user_id',
            'general_payrolls.created_at',
            'users.unit_id'
        );
        if ($tabulate != 'year(general_payrolls.created_at), month(general_payrolls.created_at)') {
            $general_payrolls->groupBy('general_payrolls.id');
        }

        if ($input['unit']) {
            $general_payrolls->where('users.unit_id', $input['unit']);
        }
        if ($input['user_id']) {
            $general_payrolls->where('user_id', $input['user_id']);
        }
        if ($input['status_id']) {
            $general_payrolls->where('status_id', $input['status_id']);
        }
        if ($input['title']) {
            $input['title'] = '%' . $input['title'] . '%';
            $general_payrolls->where('title', 'like', $input['title']);
        }
        if ($input['create_from_date']) {
            $general_payrolls->where('general_payrolls.created_at', '>=', $input['create_from_date']);
        }
        if ($input['create_to_date']) {
            $general_payrolls->where('general_payrolls.created_at', '<=', $input['create_to_date']);
        }
        if ($tabulate) {
            $groupPayrolls = clone $general_payrolls;
            $lists_column = $tabulate;
            if ($tabulate === 'users.unit_id') {
                $lists_column = 'unit_id';
            }

            if ($tabulate === 'year(general_payrolls.created_at), month(general_payrolls.created_at)') {
                $groupPayrolls = $groupPayrolls
                    ->groupBy(DB::raw($tabulate))
                    ->get();

                // dd(last(DB::getQueryLog()));
                // dd($groupPayrolls);

                foreach ($groupPayrolls as $index => $data) {

                    $tempPayrolls = clone $general_payrolls;
                    // $tempPayrolls->groupBy('general_payrolls.id');
                    $tempPayrolls->select(
                        'general_payrolls.id',
                        'general_payrolls.ref',
                        'general_payrolls.title',
                        'general_payrolls.value',
                        'general_payrolls.status_id',
                        'general_payrolls.user_id',
                        'general_payrolls.created_at',
                        'users.unit_id'
                    );
                    $value = explode('-', $data->created_at);
                    array_pop($value);
                    $value = implode('-', $value);
                    $tables[] = [
                        'title' => $value,
                        'data' => $tempPayrolls
                            ->where('general_payrolls.created_at', 'like', "$value%")
                            ->get(),
                    ];
                    unset($tempPayrolls);
                }
            } else {
                $groupPayrolls = $groupPayrolls
                    ->groupBy(DB::raw($tabulate))
                    ->get()
                    ->lists($lists_column);
                $groupPayrolls = array_unique($groupPayrolls);
                foreach ($groupPayrolls as $index => $value) {
                    $tempPayrolls = clone $general_payrolls;
                    $title = $value;
                    switch ($tabulate) {
                        case 'status_id':
                            $title = Status::find($title)->name;
                            break;
                        case 'user_id':
                            $title = UserProfile::find($title)->userName();
                            break;
                        case 'users.unit_id':
                            $title = UserUnit::find($title)->name;
                            break;
                    }
                    $tables[] = [
                        'title' => $title,
                        'data' => $tempPayrolls
                            ->where($tabulate, $value)
                            ->get(),
                    ];
                    unset($tempPayrolls);
                }
            }
        } else {
            $tables = [[
                'title' => '',
                'data' => $general_payrolls->get(),
            ]];
        }
        if (isset($input['download'])) {
            return Excel::create('GeneralPayroll_Report_' . date('Ymd-His'), function ($excel) use ($tables) {
                foreach ($tables as $table) {
                    $excel->sheet($table['title'], function ($sheet) use ($table) {
                        $sheet->loadView('payrolls.admin.reporting-table', ['datas' => $table['data']]);
                    });
                }
            })->export('xls');
        }
        return View::make('payrolls.admin.reporting', compact('tables'))->withInput($input);
    }

    public function __construct()
    {
        View::share('controller', 'Payrolls');
        View::share('types', Payroll__Main::all());
    }

}