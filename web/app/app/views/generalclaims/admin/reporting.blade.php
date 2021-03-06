@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('generalclaims.menu')
            <h3>Manage General Claims Type</h3>
        </div>
        <div style="padding:15px">
            {{Former::open_vertical()}}
            <div class="row">
                <div class="col-xs-6">
                    {{ Former::select('unit')
                    -> label('Unit')
                    -> selected(@$queryFormData['unit'])
                    -> options(UserUnit::selectOptions()) }}
                </div>
                <div class="col-xs-6">
                    {{ Former::select('user_id')
                    -> label('User')
                    -> selected(@$queryFormData['user_id'])
                    -> options(User::selectOptions(GeneralClaim__Main::$moduleId)) }}
                </div>
            </div>
            {{Former::populate(@$queryFormData)}}
            <div class="row">
                <div class="col-xs-6">
                    {{ Former::text('title')
                    -> label('Search Title') }}
                </div>
                <div class="col-xs-6">
                    {{ Former::select('status_id')
                    -> label('Status')
                    -> options(Status::selectOptions()) }}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Claims Created On:</label>
                </div>
                <div class="col-xs-6">
                    {{ Former::input('create_from_date')
                    -> type('date')
                    -> value(date('Y-m-01'))
                    -> label('From Date') }}
                </div>
                <div class="col-xs-6">
                    {{ Former::input('create_to_date')
                    -> type('date')
                    -> label('To Date') }}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    {{ Former::select('tabulate_by')
                    -> label('Tabulate By')
                    -> options([
                    '' => 'Select One',
                    'status_id' => 'Status',
                    'user_id'  => 'User',
                    'users.unit_id'  => 'Unit',
                    'year(general_claims.created_at), month(general_claims.created_at)' => 'Month',
                    ]) }}
                </div>
            </div>
            <hr>
            <br>
            <button class="btn btn-primary">Run Query</button>
            <input class="btn btn-primary" type="submit" name="download" value="Download">
            {{Former::close()}}
        </div>
    </div>
    <div class="clearfix"></div>
    <br>

    @if(isset($tables))
    <div class="col-md-12">
        <div class="innerLR border-bottom">
            <div id="leave_type_rows">
                <h3>
                Claims Query Results
                </h3>
                <hr>
                @foreach ($tables as $table)
                <br>
                <h4>
                {{$table['title']}}
                </h4>
                <br>
                @include('generalclaims.admin.reporting-table', ['datas' => $table['data'], 'showaction' => true])
                <hr>
                <br>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@stop
@section('script')
<script>
    $('[name="download"]').click(function(e){
        $('form').attr('target', '_blank');
        setTimeout(function() {
            $('form').removeAttr('target');
        }, 100);
    });
</script>
@stop
