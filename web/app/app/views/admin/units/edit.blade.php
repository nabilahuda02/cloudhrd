@extends('layouts.module')
@section('content')
<section id="units">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>
                    Edit Unit
                    {{-- <button class="btn btn-link help-btn" onclick="HelpFile.show('MANAGE_USER.md', 'Manage Users')">
                        <i class="fa fa-question-circle"></i>
                    </button> --}}
                </h2>
            </div>
            <div class="col-sm-6 section-drop-menu" >
                @include('admin.units.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('html.notifications')
                {{ Former::horizontal_open(action('AdminUnitController@update', $unit->id))
                    -> method('POST') }}
                {{ Former::hidden('_method', 'PUT') }}
                {{ Former::populate($unit) }}
                @include('admin.units.form')
                <div class="form-group">
                    <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                        <button type="button" id="delete_unit" class="btn-large btn-secondary btn pull-right">Delete</button>
                        <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
                    </div>
                </div>
                {{Former::close()}}
                {{ Former::open(action('AdminUnitController@destroy', $unit->id))->id('delete_form') }}
                {{ Former::hidden('_method', 'DELETE') }}
                {{ Former::close() }}
            </div>
        </div>
    </div>
</section>
@stop
@section('script')
<script>
document.getElementById('delete_unit').onclick = function() {
if(confirm('Are you sure you want to remove this user?'))
document.getElementById('delete_form').submit();
}
</script>
@stop
