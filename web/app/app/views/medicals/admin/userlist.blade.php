@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('medicals.menu')
            @if(isset($entitlement_user))
                <h3>Manage {{User::fullName($entitlement_user->id)}}'s Entitlements</h3>
            @else
                <h3>Manage Entitlements</h3>
            @endif
        </div>
        {{Form::select('user',Helper::userArray(), @$entitlement_user->id, ['placeholder' => 'Select an Employee', 'id' => 'user_select'])}}
        <button id="edit_employee" class="btn btn-primary">Manage</button>
        <div class="clearfix"></div>
        <br>
        <hr>
        @if(isset($entitlement_user))
        {{Form::open()}}
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Claim Type</th>
                    <th class="text-center">Default</th>
                    <th class="text-center">Override</th>
                    <th class="text-center">Utilized</th>
                    <th class="text-center">Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach(MedicalClaim__Type::all() as $medicalType)
                <tr>
                    <td>{{$medicalType->name}}</td>
                    <td class="text-center">{{$medicalType->default_entitlement}}</td>
                    <td class="text-center"><input type="number" name="type[{{$medicalType->id}}]" value="{{$medicalType->effective_user_entitlement_override($entitlement_user->id)}}"></td>
                    <td class="text-center">{{Helper::currency($medicalType->utilized_user_entitlement($entitlement_user->id))}}</td>
                    <td class="text-center">{{Helper::currency($medicalType->user_entitlement_balance($entitlement_user->id))}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary pull-right">Save</button>
        <div class="clearfix"></div>
        {{Form::close()}}
        @endif
    </div>
</div>
@stop
@section('script')
<script>
$('#edit_employee').click(function(){
window.location.href="/medical/admin/entitlement/" + $('#user_select').val();
})
</script>
@stop
