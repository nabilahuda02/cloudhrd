<?php

class GeneralClaimsController extends \BaseController
{

    /**
     * Display a listing of claims
     *
     * @return Response
     */
    public function index()
    {
        $downlines = Auth::user()->getDownline(GeneralClaim__Main::$moduleId);
        return View::make('generalclaims.index', compact('downlines'));
    }

    /**
     * Show the form for creating a new claim
     *
     * @return Response
     */
    public function create()
    {
        return View::make('generalclaims.create');
    }

    /**
     * Store a newly created claim in storage.
     *
     * @return Response
     */
    public function store()
    {

        $validator = Validator::make($data = Input::all(), GeneralClaim__Main::$rules);

        if ($validator->fails()) {
            Session::flash('NotifyDanger', 'Error Creating Claim');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Session::flash('NotifySuccess', 'Claim Created Successfully');

        $main = $data;
        $main['upload_hash'] = md5(microtime());

        unset($main['entries']);

        if (!isset($main['user_id']) || (isset($main['user_id']) && !in_array($main['user_id'], Auth::user()->getDownline(GeneralClaim__Main::$moduleId)))) {
            $main['user_id'] = Auth::user()->id;
        }

        $claim = GeneralClaim__Main::create($main);

        $claim->ref = 'GC-' . $claim->id;
        $claim->save();

        $token = Input::get('noonce');
        Upload::where('imageable_type', $token)->update([
            'imageable_type' => 'GeneralClaim__Main',
            'imageable_id' => $claim->id,
        ]);

        foreach ($data['entries'] as $entryJson) {
            $entry = json_decode($entryJson, true);
            $entry['claim_id'] = $claim->id;
            $entry['receipt_date'] = Helper::short_date_to_mysql($entry['receipt_date']);
            GeneralClaim__Entry::create($entry);
        }

        $claim->setStatus(1);

        return Redirect::route('claims.index');
    }

    /**
     * Display the specified claim.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $claim = GeneralClaim__Main::findOrFail($id);
        if (!$claim->canView()) {
            return Redirect::action('claims.index');
        }

        return View::make('generalclaims.show', compact('claim'));
    }

    /**
     * Show the form for editing the specified claim.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $claim = GeneralClaim__Main::find($id);
        if (!$claim->canEdit()) {
            return Redirect::action('claims.index');
        }

        return View::make('generalclaims.edit', compact('claim'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $claim = GeneralClaim__Main::findOrFail($id);

        $data = Input::all();

        if (isset($data['user_id'])) {
            unset($data['user_id']);
        }

        /* update status */
        if (isset($data['_status']) && isset($data['status_id']) && (
            $claim->canApprove() ||
            $claim->canReject() ||
            $claim->canCancel() ||
            $claim->canVerify())
        ) {
            Session::flash('NotifySuccess', 'Status Updated Successfully');
            $claim->setStatus($data['status_id']);
            return Redirect::action('claims.index');
        }

        if (!$claim->canEdit()) {
            return Redirect::action('claims.index');
        }

        $validator = Validator::make($data, GeneralClaim__Main::$rules);

        if ($validator->fails()) {
            Session::flash('NotifyDanger', 'Error Updating Claim');
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Session::flash('NotifySuccess', 'Claim Updated Successfully');
        $claim->update($data);

        GeneralClaim__Entry::where('claim_id', $claim->id)->delete();
        foreach ($data['entries'] as $entryJson) {
            $entry = json_decode($entryJson, true);
            $entry['claim_id'] = $claim->id;
            $entry['receipt_date'] = Helper::short_date_to_mysql($entry['receipt_date']);
            GeneralClaim__Entry::create($entry);
        }

        $claim->audits()->create([
            'ref' => $claim->ref,
            'type' => 2,
            'data' => $claim->toArray(),
        ]);

        return Redirect::route('claims.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Session::flash('NotifySuccess', 'Claim Deleted Successfully');
        GeneralClaim__Main::destroy($id);
        return Redirect::route('claims.index');
    }

    public function togglePaid($id)
    {
        Session::flash('NotifySuccess', 'Medical Claim Paid Status Updated Successfully');
        $claim = GeneralClaim__Main::find($id);
        $claim->update([
            'is_paid' => !$claim->is_paid,
        ]);
        return Redirect::route('claims.show', $id);
    }

    /**
     * Administration section
     */

    public function getAdminTypes()
    {
        return View::make('generalclaims.admin.types');
    }

    public function getAdminReporting()
    {
        return View::make('generalclaims.admin.reporting');
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

        $general_claims = GeneralClaim__Main::join('users', 'users.id', '=', 'general_claims.user_id');
        $general_claims->select(
            'general_claims.id',
            'general_claims.ref',
            'general_claims.title',
            'general_claims.value',
            'general_claims.status_id',
            'general_claims.user_id',
            'general_claims.created_at',
            'users.unit_id'
        );
        if ($tabulate != 'year(general_claims.created_at), month(general_claims.created_at)') {
            $general_claims->groupBy('general_claims.id');
        }

        if ($input['unit']) {
            $general_claims->where('users.unit_id', $input['unit']);
        }
        if ($input['user_id']) {
            $general_claims->where('user_id', $input['user_id']);
        }
        if ($input['status_id']) {
            $general_claims->where('status_id', $input['status_id']);
        }
        if ($input['title']) {
            $input['title'] = '%' . $input['title'] . '%';
            $general_claims->where('title', 'like', $input['title']);
        }
        if ($input['create_from_date']) {
            $general_claims->where('general_claims.created_at', '>=', $input['create_from_date']);
        }
        if ($input['create_to_date']) {
            $general_claims->where('general_claims.created_at', '<=', $input['create_to_date']);
        }
        if ($tabulate) {
            $groupClaims = clone $general_claims;
            $lists_column = $tabulate;
            if ($tabulate === 'users.unit_id') {
                $lists_column = 'unit_id';
            }

            if ($tabulate === 'year(general_claims.created_at), month(general_claims.created_at)') {
                $groupClaims = $groupClaims
                    ->groupBy(DB::raw($tabulate))
                    ->get();

                // dd(last(DB::getQueryLog()));
                // dd($groupClaims);

                foreach ($groupClaims as $index => $data) {

                    $tempClaims = clone $general_claims;
                    // $tempClaims->groupBy('general_claims.id');
                    $tempClaims->select(
                        'general_claims.id',
                        'general_claims.ref',
                        'general_claims.title',
                        'general_claims.value',
                        'general_claims.status_id',
                        'general_claims.user_id',
                        'general_claims.created_at',
                        'users.unit_id'
                    );
                    $value = explode('-', $data->created_at);
                    array_pop($value);
                    $value = implode('-', $value);
                    $tables[] = [
                        'title' => $value,
                        'data' => $tempClaims
                            ->where('general_claims.created_at', 'like', "$value%")
                            ->get(),
                    ];
                    unset($tempClaims);
                }
            } else {
                $groupClaims = $groupClaims
                    ->groupBy(DB::raw($tabulate))
                    ->get()
                    ->lists($lists_column);
                $groupClaims = array_unique($groupClaims);
                foreach ($groupClaims as $index => $value) {
                    $tempClaims = clone $general_claims;
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
                        'data' => $tempClaims
                            ->where($tabulate, $value)
                            ->get(),
                    ];
                    unset($tempClaims);
                }
            }
        } else {
            $tables = [[
                'title' => '',
                'data' => $general_claims->get(),
            ]];
        }
        if (isset($input['download'])) {
            return Excel::create('GeneralClaim_Report_' . date('Ymd-His'), function ($excel) use ($tables) {
                foreach ($tables as $table) {
                    $excel->sheet($table['title'], function ($sheet) use ($table) {
                        $sheet->loadView('generalclaims.admin.reporting-table', ['datas' => $table['data']]);
                    });
                }
            })->export('xls');
        }
        return View::make('generalclaims.admin.reporting', compact('tables'))->withInput($input);
    }

    public function __construct()
    {
        View::share('controller', 'General Claims');
        View::share('types', GeneralClaim__Main::all());
    }

}
