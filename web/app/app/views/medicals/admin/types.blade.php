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
                        -> placeholder('#696969,#c4c4c4')
                        -> label('Colors') }}
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
            }
        });
    </script>
@stop