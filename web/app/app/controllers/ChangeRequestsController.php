<?php

class ChangeRequestsController extends \BaseController
{

    /**
     * Display a listing of the resource.
     * GET /changerequests
     *
     * @return Response
     */
    public function index()
    {
        $downlines = Auth::user()->getDownline(ChangeRequest__Main::$moduleId);
        return View::make('changerequests.index', compact('downlines'));
    }

    /**
     * Show the form for creating a new resource.
     * GET /changerequests/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /changerequests
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     * GET /changerequests/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * GET /changerequests/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /changerequests/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /changerequests/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function __construct()
    {
        View::share('controller', 'Change Requests');
    }

}
