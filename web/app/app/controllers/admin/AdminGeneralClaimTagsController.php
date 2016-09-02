<?php

class AdminGeneralClaimTagsController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return GeneralClaim__Tag::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::all();
        $validator = Validator::make($data, GeneralClaim__Tag::$rules);

        if ($validator->fails()) {
            return App::abort(400);
        }
        $tag = GeneralClaim__Tag::create($data);
        return $tag;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $tag = GeneralClaim__Tag::findOrFail($id);

        $validator = Validator::make($data = Input::all(), GeneralClaim__Tag::$rules);

        if ($validator->fails()) {
            return App::abort(400, $validator->errors());
        }

        $tag->update($data);

        return $tag;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        GeneralClaim__Tag::destroy($id);
        return array();
    }

}
