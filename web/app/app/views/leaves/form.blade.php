@section('style')
<style>
    .ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight, .ui-state-highlight{
        border: 0px solid #4a89dc!important;
    }
    .ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br{
        border-radius: 0px;
    }
    .ui-widget-content {
        border: 0px solid #aaa;
    }
    .ui-widget-header{
        border: 0px solid #aaa;
        background: #eb6a5a;
    }
    .ui-datepicker{
        width: 100%;
    }
    .ui-state-highlight a, .ui-widget-content .ui-state-highlight a, .ui-widget-header .ui-state-highlight
    .ui-state-active a, .ui-widget-content .ui-state-active a, .ui-widget-header .ui-state-active a, .ui-state-highlight span{
        background: #4a89dc!important;
        color: #000!important;
        border: 0px solid #4a89dc!important;
    }
    .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default{
        background: #ca5a45;
        color: #fff;
    }
    .ui-datepicker td span, .ui-datepicker td a{
        padding: 0.8em;
    }
    td {
        text-align: center;
    }
</style>
@stop

{{ Former::select('leave_type_id')
    -> label('Type')
    -> placeholder('Choose')
    -> options($types->lists('name', 'id') ,null)
    -> class('form-control col-md-4')
    ->required() }}

<div class="form-group">
    <label for="" class="control-label col-lg-2 col-sm-4">Dates</label>
    <div class="col-lg-5 col-sm-5" id="datepicker"></div>
</div>

{{ Former::text('dates')
    ->readonly()
    ->required() }}
