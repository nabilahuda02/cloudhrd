@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            <h3>Edit {{$module->name}} Module</h3>
        </div>
        {{ Former::horizontal_open(action('AdminModuleController@update', $module->id))
        -> rules(['name' => 'required'])
        -> method('POST') }}
        {{ Former::hidden('_method', 'PUT') }}
        {{ Former::populate($module) }}
        {{ Former::text('name')
        -> required() }}
        {{ Former::radios('verifier')
        ->radios(array(
        'None' => array('name' => 'verifier', 'value' => '-2'),
        'Admin and Module Owners' => array('name' => 'verifier', 'value' => '-1'),
        'Unit Head' => array('name' => 'verifier', 'value' => '0'),
        ))
        ->stacked() }}
        {{ Former::radios('approver')
        ->radios(array(
        'Admin and Module Owners' => array('name' => 'approver', 'value' => '-1'),
        'Unit Head' => array('name' => 'approver', 'value' => '0'),
        ))
        ->stacked() }}
        <div class="form-group">
            <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
            </div>
        </div>
        {{ Former::close() }}
    </div>
    <div class="col-md-12">
        <div>
            <div id="module_owner">
                <div class="page-header">
                    <h3>Set {{$module->name}} Module Owners</h3>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {{ Former::select('user_id')
                        -> options(Helper::userArray())
                        -> placeholder('Choose a user') }}
                    </div>
                    <div class="clearfix"></div>
                    <br>
                </div>
                <hr>
                <br>
            </div>
        </div>
    </div>
</div>

@stop
@section('script')
<script>
$('#module_owner').duplicator({
row: ".row",
remotes: {
post: '/moduleadmin/admins/{{$module->id}}',
put: '/moduleadmin/admins/{{$module->id}}',
delete: '/moduleadmin/admins/{{$module->id}}',
get: '/moduleadmin/admins/{{$module->id}}'
}
});
</script>
@stop