//= require jquery
//= require lodash/dist/lodash.min
//= require jqueryui/jquery-ui.min
//= require backbone/backbone
//= require bootstrap/dist/js/bootstrap.min
//= require dropzone/downloads/dropzone.min
//= require raphael/raphael-min
//= require morrisjs/morris.min
//= require datatables/media/js/jquery.dataTables.min
//= require jquery.serializeJSON/jquery.serializejson.min
//= require bootbox/bootbox
//= require multidatespicker
//= require x-editable/dist/bootstrap3-editable/js/bootstrap-editable
//= require datatables
//= require app/duplicator/duplicator
//= require app/jquery.avatar.js
//= require app/lib/feedback
//= require_self

Dropzone.autoDiscover = false;

;
var tbl;

(function() {

    // $('button[type=submit],input[type=submit]').click(function() {
    //     var btn = $(this)
    //     btn.button('loading')
    //         .val('Submitting...')
    //         .text('Submitting...');
    // });
    // 

    window.init_datepicker = function(){
        $('input[data-type=date]').datepicker({
            dateFormat: app_locale.short_date
        });
    }
    init_datepicker();

    var search = $('#searchinput');
    var result = $('#search-results');

    search.blur(function(){
      setTimeout(function() {
        if(!result.is(':hidden'))
          result.hide();
          search.val('');
      }, 300);
    });

    search.keyup(function(e) {
        var value = search.val().trim();
        if (e.keyCode === 27) {
            search.val('');
            if (!result.is(':hidden'))
                result.hide();
        } else if (value.length > 3) {
            $.post('/ajax/quick-search', {
                search: value
            }, function(response) {
                result.html(response).show();
            });
        }
    });

    if($('.DT').length > 0) {
        $('.DT').each(function(){
          var target = $(this);
          tbl = target.DataTable({
            "ajax": '/data/' + target.data('path'),
            "aaSorting": []
          });
        });;
    };

    new Feedback({
        url: '/wall/feedback'
    });

    $('.feedback-btn')
        .removeClass('feedback-bottom-right')
        .addClass('feedback-bottom-left btn-danger btn')
        .hover(function(){
            $('.feedback-btn').animate({
                left: '5px'
            })
        }, function(){
            $('.feedback-btn').animate({
                left: '-122px'
            })
        })
        .append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-exclamation-triangle"></span> ');

}).call(this);