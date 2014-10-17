<?php

class DataController extends BaseController
{
  public function getLeaves()
  {
    /**
     * Status, Reference Number, Type, Duration, Action
     */
    $leaves = DB::table('leaves')->select(['leaves.id', 'status.name as "status_name"', 'ref', 'leave_types.name', 'total'])
      -> where('user_id', Auth::user()->id)
      -> orderBy('leaves.ref', 'desc')
      -> join('status', 'status.id', '=', 'leaves.status_id')
      -> join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id');
    return Datatables::of($leaves)
      ->add_column('action', "{{View::make('leaves.actions-table', compact('id'))->render()}}")
      ->remove_column('id')
      ->make();
  }

  public function getOtherLeaves()
  {
    /**
     * User, Status, Reference Number, Type, Duration, Action
     */
    $downline = Auth::user()->getDownline(Leave__Main::$moduleId);
    if(count($downline) > 0) {
      $leaves = DB::table('leaves')->select(['leaves.id','status.name as "status_name"',  'user_profiles.first_name', 'ref', 'leave_types.name', 'total'])
        -> whereIn('leaves.user_id', $downline)
        -> orderBy('leaves.ref', 'desc')
        -> join('users', 'users.id', '=', 'leaves.user_id')
        -> join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
        -> join('status', 'status.id', '=', 'leaves.status_id')
        -> join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id');
      } else {
        $leaves = Leave__Main::whereNull('status_id')->select(['leaves.id','status_id',  'user_id', 'ref', 'leave_type_id', 'total']);
      }
    return Datatables::of($leaves)
      ->add_column('action', "{{View::make('leaves.actions-table', compact('id'))->render()}}")
      ->remove_column('id')
      ->make();
  }


  public function getMedicalClaims()
  {
    /**
     * Status Clinic  Reference No  Claim Type  Amount  Action
     */
    $medical_claims = DB::table('medical_claims')->select(
      [ 'medical_claims.id', 
        'status.name as "status_name"', 
        'ref', 
        'medical_claim_types.name', 
        'total'])
      -> where('user_id', Auth::user()->id)
      -> orderBy('medical_claims.ref', 'desc')
      -> join('status', 'status.id', '=', 'medical_claims.status_id')
      -> join('medical_claim_types', 'medical_claim_types.id', '=', 'medical_claims.medical_claim_type_id');
    return Datatables::of($medical_claims)
      ->add_column('action', "{{View::make('medicals.actions-table', compact('id'))->render()}}")
      ->remove_column('id')
      ->make();
  }

  public function getOtherMedicalClaims()
  {
    /**
     * User Status  Clinic  Reference No  Claim Type  Amount  Action
     */
    $downline = Auth::user()->getDownline(MedicalClaim__Main::$moduleId);
    if(count($downline) > 0) {
      $medical_claims = DB::table('medical_claims')->select(
      [ 'medical_claims.id', 
        'status.name as "status_name"', 
        'user_profiles.first_name',
        'ref', 
        'medical_claim_types.name', 
        'total'])
      -> whereIn('medical_claims.user_id', $downline)
      -> orderBy('medical_claims.ref', 'desc')
      -> join('users', 'users.id', '=', 'medical_claims.user_id')
      -> join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
      -> join('status', 'status.id', '=', 'medical_claims.status_id')
      -> join('medical_claim_types', 'medical_claim_types.id', '=', 'medical_claims.medical_claim_type_id');
      } else {
        $medical_claims = MedicalClaim__Main::whereNull('status_id')->select(
          ['medical_claims.id', 
            'status_id', 
            'user_id', 
            'ref', 
            'medical_claim_type_id', 
            'medical_claim_panel_clinic_id',
            'total']);
      }
    return Datatables::of($medical_claims)
      ->add_column('action', "{{View::make('medicals.actions-table', compact('id'))->render()}}")
      ->remove_column('id')
      ->make();
  }
  
  public function getGeneralClaims()
  {
    /**
     * Status  Reference No  Request Date  Claim Description  Amount  Action
     */
    $general_claims = DB::table('general_claims')->select(
      [ 'general_claims.id', 
        'status.name as "status_name"', 
        'ref', 
        DB::Raw('date(general_claims.created_at) as date'),
        'title', 
        'value'])
      -> orderBy('general_claims.ref', 'desc')
      -> where('user_id', Auth::user()->id)
      -> join('status', 'status.id', '=', 'general_claims.status_id');
    return Datatables::of($general_claims)
      ->add_column('action', "{{View::make('generalclaims.actions-table', compact('id'))->render()}}")
      ->remove_column('id')
      ->make();
  }

  public function getOtherGeneralClaims()
  {
    /**
     * User Status  Reference No  Request Date  Claim Description  Amount  Action
     */
    $downline = Auth::user()->getDownline(GeneralClaim__Main::$moduleId);
    if(count($downline) > 0) {
      $general_claims = DB::table('general_claims')->select(
      [ 'general_claims.id', 
        'status.name as "status_name"', 
        'user_profiles.first_name',
        'ref', 
        DB::Raw('date(general_claims.created_at) as date'),
        'title', 
        'value'])
      -> orderBy('general_claims.ref', 'desc')
      -> whereIn('general_claims.user_id', $downline)
      -> join('users', 'users.id', '=', 'general_claims.user_id')
      -> join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
      -> join('status', 'status.id', '=', 'general_claims.status_id');
      } else {
        $general_claims = GeneralClaim__Main::whereNull('status_id')->select(
          ['general_claims.id', 
            'status_id', 
            'user_id', 
            'ref', 
            'created_at',
            'title', 
            'value']);
      }
    return Datatables::of($general_claims)
      ->add_column('action', "{{View::make('generalclaims.actions-table', compact('id'))->render()}}")
      ->remove_column('id')
      ->make();
  }
  
  public function getRoomBookings()
  {
    /**
     * Status Reference No  Room  Slots Action
     */
    $room_bookings = DB::table('room_bookings')->select(
      [ 'room_bookings.id', 
        'status.name as "status_name"', 
        'ref',
        'room_bookings.booking_date',
        'room_booking_rooms.name',
        DB::Raw('group_concat(concat("(", TIME_FORMAT(lookup_timing_slots.start, "%l:%i %p"), " - ", TIME_FORMAT(lookup_timing_slots.end, "%l:%i %p"), ") ", lookup_timing_slots.name) SEPARATOR "<br/> ") as slots')])
      -> where('user_id', Auth::user()->id)
      -> orderBy('room_bookings.ref', 'desc')
      -> join('room_booking_timing_slots', 'room_booking_timing_slots.room_booking_id', '=', 'room_bookings.id')
      -> join('lookup_timing_slots', 'lookup_timing_slots.id', '=', 'room_booking_timing_slots.lookup_timing_slot_id')
      -> join('room_booking_rooms', 'room_booking_rooms.id', '=', 'room_bookings.room_booking_room_id')
      -> join('status', 'status.id', '=', 'room_bookings.status_id')
      -> groupBy('room_bookings.id');
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
    if(count($downline) > 0) {
    $room_bookings = DB::table('room_bookings')->select(
      [ 'room_bookings.id', 
        'status.name as "status_name"', 
        'ref',
        'user_profiles.first_name',
        'room_bookings.booking_date',
        'room_booking_rooms.name',
        DB::Raw('group_concat(concat("(", TIME_FORMAT(lookup_timing_slots.start, "%l:%i %p"), " - ", TIME_FORMAT(lookup_timing_slots.end, "%l:%i %p"), ") ", lookup_timing_slots.name) SEPARATOR "<br/> ") as slots')])
      -> whereIn('room_bookings.user_id', $downline)
      -> orderBy('room_bookings.ref', 'desc')
      -> join('room_booking_timing_slots', 'room_booking_timing_slots.room_booking_id', '=', 'room_bookings.id')
      -> join('lookup_timing_slots', 'lookup_timing_slots.id', '=', 'room_booking_timing_slots.lookup_timing_slot_id')
      -> join('users', 'users.id', '=', 'room_bookings.user_id')
      -> join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
      -> join('room_booking_rooms', 'room_booking_rooms.id', '=', 'room_bookings.room_booking_room_id')
      -> join('status', 'status.id', '=', 'room_bookings.status_id')
      -> groupBy('room_bookings.id');;
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
      -> join('users', 'users.id', '=', 'audits.user_id')
      -> join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
      -> orderBy('audits.updated_at', 'desc');
    return Datatables::of($audits)
      ->add_column('action', "<button class='btn btn-primary viewauditdetails' data-auditdata='{{json_encode(\$data)}}'><i class='fa fa-eye'></i></button>")
      ->remove_column('id')
      ->remove_column('data')
      ->remove_column('auditable_type')
      ->make();
  }


}