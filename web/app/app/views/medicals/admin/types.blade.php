@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="col-md-12">
        <div>
            <div id="medical_type_rows">
                <div class="page-header">
                    @include('medicals.menu')
                    <h3>Medial Claim Types</h3>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        {{ Former::text('name')
                        -> label('Entitlement Name')
                        -> placeholder('Outpatient Claim') }}
                    </div>
                    <div class="col-md-3">
                        {{ Former::number('default_entitlement')
                        -> label('Default Entitlement') }}
                    </div>
                    <div class="col-md-3">
                        {{ Former::text('colors')
                        -> placeholder('#696969')
                        -> label('Color')
                        -> style('color:white') }}
                    </div>
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
</div>

@stop
@section('script')
    <script>
        $('#medical_type_rows').duplicator({
            row: ".row",
            remotes: {
                post: '/medicaltype',
                put: '/medicaltype',
                delete: '/medicaltype',
                get: '/medicaltype'
            },
            onAddRow: function(el) {
                var input = $('[name=colors]', el);
                var initial = input.val() || '555555';
                initial = initial.split(',').shift().replace('#', '');
                input.css('background-color', '#' + initial);
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