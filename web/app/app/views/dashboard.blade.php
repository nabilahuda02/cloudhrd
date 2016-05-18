@extends('layouts.module')
@section('content')
<div class="container">
  <div class="row" id="dashboard-wrapper">

    @foreach(Leave__Type::all() as $type)
      <div class="col-sm-3 col-xs-6">
        <div class="widget">
          <div class="chart entitlement" data-path="leave/{{ $type->id }}" id="leave-entitlement_{{$type->id}}">
          </div>
          <div class="widget-footer">
            <h4><a href="/leave">Leave: {{ $type->name }}</a></h4>
          </div>
        </div>
      </div>
    @endforeach

    @foreach(Leave__Type::all() as $type)
      <div class="col-sm-3 col-xs-6">
        <div class="widget">
          <div class="chart leave-entitlement" id="leave-graph_{{$type->id}}" data-id="{{ $type->id }}">
          </div>
          <div class="widget-footer">
            <h4><a href="/leave">Leave: {{ $type->name }}</a></h4>
          </div>
        </div>
      </div>
    @endforeach

    @foreach(MedicalClaim__Type::all() as $type)
      <div class="col-sm-3 col-xs-6">
        <div class="widget">
          <div class="chart entitlement" data-path="medical/{{ $type->id }}" id="medical-entitlement_{{$type->id}}">
          </div>
          <div class="widget-footer">
            <h4><a href="/medical">Medical Claim: {{ $type->name }}</a></h4>
          </div>
        </div>
      </div>
    @endforeach

    @foreach(MedicalClaim__Type::all() as $type)
      <div class="col-sm-3 col-xs-6">
        <div class="widget">
          <div class="chart medical-graph" id="medical-graph_{{$type->id}}" data-id="{{ $type->id }}">
          </div>
          <div class="widget-footer">
            <h4><a href="/medical">Medical Claim: {{ $type->name }}</a></h4>
          </div>
        </div>
      </div>
    @endforeach

    <div class="col-sm-3 col-xs-6">
      <div class="widget">
        <div class="chart status" id="leave-status" data-path="leaves">
        </div>
        <div class="widget-footer">
          <h4><a href="/leave">Leave Status</a></h4>
        </div>
      </div>
    </div>

    <div class="col-sm-3 col-xs-6">
      <div class="widget">
        <div class="chart status" id="medical-status" data-path="medical_claims">
        </div>
        <div class="widget-footer">
          <h4><a href="/medical">Medical Claim Status</a></h4>
        </div>
      </div>
    </div>

    <div class="col-sm-3 col-xs-6">
      <div class="widget">
        <div class="chart status" id="claims-status" data-path="general_claims">
        </div>
        <div class="widget-footer">
          <h4><a href="/claims">Claims Status</a></h4>
        </div>
      </div>
    </div>

    <div class="col-sm-3 col-xs-6">
      <div class="widget">
        <div class="chart status" id="booking-status" data-path="room_bookings">
        </div>
        <div class="widget-footer">
          <h4><a href="/booking">Booking Status</a></h4>
        </div>
      </div>
    </div>

  </div>
</div>
@stop
@section('script')
  {{Asset::push('js','app/dash')}}
@stop