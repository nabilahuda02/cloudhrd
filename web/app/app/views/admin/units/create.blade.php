@extends('layouts.module')
@section('content')
<section id="units">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>
                    Create New Unit
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
                {{ Former::horizontal_open(action('AdminUnitController@store'))
                    -> method('POST') }}
                @include('admin.units.form')
                <div class="form-group">
                    <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                        <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
                    </div>
                </div>
                {{ Former::close() }}
            </div>
        </div>
    </div>
</section>
@stop
