@extends('layouts.module')
@section('content')
<div class="col-md-12">
    @include('html.notifications')
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
                <div class="col-md-3">
                    {{ Former::text('colors')
                    -> label('Color')
                    -> placeholder('#696969')
                    -> style('color:white') }}
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
                    <label for="display_wall">Show</label><br>
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
        },
        onAddRow: function(el) {
            var input = $('[name=colors]', el);
            var initial = input.val() || '555555';
            if(initial.indexOf(',') > -1) {
                initial = initial.split(',').shift();
            }
            initial = initial.replace('#', '');
            input.css('background-color', '#' + initial)
                .val('#' + initial);
            input.colpick({
                onSubmit: function(hsl, hex) {
                    $('[name=colors]', el).val('#' + hex);
                    input.css('background-color', '#' + hex);
                    input.colpickHide();
                }
            }).colpickSetColor(initial)
        }
    });
</script>

@stop
