@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">

    @include('html.notifications')

    <div class="col-md-12">
        <div>
            <div id="leave_type_rows">
                <div class="page-header">
                    @include('leaves.menu')
                    <h3>Leave Types</h3>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {{ Former::text('name')
                        -> label('Leave Type Name')
                        -> placeholder('Annual Leave') }}
                    </div>
                    <div class="col-md-3">
                        {{ Former::number('default_entitlement')
                        -> label('Default Entitlement') }}
                    </div>
                    <div class="col-md-2">
                        {{ Former::text('colors')
                        -> label('Colors')
                        -> placeholder('#696969,#c4c4c4') }}
                    </div>
                    <div class="col-md-1">
                        <label for="past">Past</label><br>
                        <input id="past" type="checkbox" name="past" value="1">
                    </div>
                    <div class="col-md-1">
                        <label for="future">Future</label><br>
                        <input id="future" type="checkbox" name="future" value="1">
                    </div>
                    <!-- <div class="col-md-1">
                        <label for="display_calendar">Calendar</label><br>
                        <input id="display_calendar" type="checkbox" name="display_calendar" value="1">
                    </div> -->
                    <div class="col-md-1">
                        <label for="display_wall">Wall</label><br>
                        <input id="display_wall" type="checkbox" name="display_wall" value="1">
                    </div>
                    <div class="clearfix"></div>
                    <br>
                </div>
                <hr>
                <br>
            </div>
        </div>
    </div>

    @stop
    @section('script')
    <script>

        $('#leave_type_rows').duplicator({
            row: ".row",
            remotes: {
                post: '/leavetype',
                put: '/leavetype',
                delete: '/leavetype',
                get: '/leavetype'
            }
        });
    </script>

    @stop