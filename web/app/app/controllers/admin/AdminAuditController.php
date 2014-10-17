<?php

class AdminAuditController extends \BaseController {

  /**
   * Display a listing of audits
   *
   * @return Response
   */
  public function getIndex()
  {
    return View::make('admin.audits.index');
  }

  public function __construct()
  {
    View::share('controller', 'Audit');
  }

}