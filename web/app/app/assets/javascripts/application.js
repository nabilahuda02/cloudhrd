//= require jquery
//= require lodash/dist/lodash.min
//= require jqueryui/jquery-ui.min
//= require bootstrap/dist/js/bootstrap.min
//= require moment/min/moment.min
//= require dropzone/downloads/dropzone.min
//= require raphael/raphael-min
//= require morrisjs/morris.min
//= require datatables/media/js/jquery.dataTables.min
//= require jquery.serializeJSON/jquery.serializejson.min
//= require bootbox/bootbox
//= require fullcalendar/dist/fullcalendar.min
//= require multidatespicker
//= require datatables
//= require app/duplicator/duplicator
//= require_self

$('button[type=submit],input[type=submit]').click(function () {
    var btn = $(this)
    btn.button('loading')
        .val('Submitting...')
        .text('Submitting...');
});