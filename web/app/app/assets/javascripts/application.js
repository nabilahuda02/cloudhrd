//=require ../packages/jquery/dist/jquery.min.js
//=require ../packages/lodash/dist/lodash.min.js
//=require ../packages/jqueryui/jquery-ui.min.js
//=require ../packages/backbone/backbone.js
//=require ../packages/bootstrap/dist/js/bootstrap.min.js
//=require ../packages/dropzone/downloads/dropzone.min.js
//=require ../packages/raphael/raphael-min.js
//=require ../packages/morrisjs/morris.min.js
//=require ../packages/datatables/media/js/jquery.dataTables.min.js
//=require ../packages/jquery.serializeJSON/jquery.serializejson.min.js
//=require ../packages/bootbox/bootbox.js
//=require ../packages/Multiple-Dates-Picker-for-jQuery-UI/jquery-ui.multidatespicker.js
//=require ../packages/datatables-bootstrap3-plugin/media/js/datatables-bootstrap3.min.js
//=require app/lib/duplicator.js
//=require ../packages/tapatar/dist/tapatar.min.js
//=require ../packages/colpick/js/colpick.js
//=require ../packages/compare-versions/index.js
//=require ../packages/moment/min/moment.min.js
//=require ../packages/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js
//=require app/help.js
//=require app/changelog.js

Dropzone.autoDiscover = false;

;
var tbl;

(function() {

    $('.click-once').click(function(e) {
        var btn = $(this);
        if(btn.parents('form')[0].checkValidity()) {
            btn.button('loading')
            .val('Submitting...')
            .text('Submitting...');
        }
    });

    window.init_datepicker = function(){
        // causes error in login
        if(window.app_locale) {
            $('input[data-type=date]').datepicker({
                dateFormat: app_locale.short_date
            });
        }
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

    // menu begin
    $('#cssmenu li.active').addClass('open').children('ul').show();
    $('#cssmenu li.has-sub>a').on('click', function(){
        console.log('click');
        $(this).removeAttr('href');
        var element = $(this).parent('li');
        if (element.hasClass('open')) {
            element.removeClass('open');
            element.find('li').removeClass('open');
            element.find('ul').slideUp(200);
        } else {
            element.addClass('open');
            element.children('ul').slideDown(200);
            element.siblings('li').children('ul').slideUp(200);
            element.siblings('li').removeClass('open');
            element.siblings('li').find('li').removeClass('open');
            element.siblings('li').find('ul').slideUp(200);
        }
    });
    // menu end

}).call(this);