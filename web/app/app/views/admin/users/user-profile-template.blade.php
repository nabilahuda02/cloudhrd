@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    <div class="row">
        <div class="col-md-12">
            @include('html.notifications')
            <div class="page-header">
                @include('admin.users.menu')
                <h3>Manage User Template</h3>
            </div>
            {{ Former::vertical_open(action('AdminUserController@postManageTemplate')) }}
            {{ Former::populate($custom_fields) }}
            <div class="form-group">
                {{Former::text('user_field_00')
                    ->label('Custom Field 1')}}
                {{Former::text('user_field_01')
                    ->label('Custom Field 2')}}
                {{Former::text('user_field_02')
                    ->label('Custom Field 3')}}
                {{Former::text('user_field_03')
                    ->label('Custom Field 4')}}
                {{Former::text('user_field_04')
                    ->label('Custom Field 5')}}
                {{Former::text('user_field_05')
                    ->label('Custom Field 6')}}
                {{Former::text('user_field_06')
                    ->label('Custom Field 7')}}
                {{Former::text('user_field_07')
                    ->label('Custom Field 8')}}
                {{Former::text('user_field_08')
                    ->label('Custom Field 9')}}
                {{Former::text('user_field_09')
                    ->label('Custom Field 10')}}
                <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
            </div>
            {{ Former::close() }}
        </div>
    </div>
</div>
@stop