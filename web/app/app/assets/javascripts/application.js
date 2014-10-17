//= require jquery
//= require lodash/dist/lodash.min
//= require jqueryui/jquery-ui.min
//= require bootstrap/dist/js/bootstrap.min
//= require dropzone/downloads/dropzone.min
//= require raphael/raphael-min
//= require morrisjs/morris.min
//= require datatables/media/js/jquery.dataTables.min
//= require jquery.serializeJSON/jquery.serializejson.min
//= require bootbox/bootbox
//= require multidatespicker
//= require datatables
//= require app/duplicator/duplicator
//= require app/jquery.avatar.js
//= require_self

;
(function() {

    $('button[type=submit],input[type=submit]').click(function() {
        var btn = $(this)
        btn.button('loading')
            .val('Submitting...')
            .text('Submitting...');
    });

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
        var tbl;
        $('.DT').each(function(){
          var target = $(this);
          tbl = target.DataTable({
            "ajax": '/data/' + target.data('path'),
            "aaSorting": []
          });
        });;
    };

}).call(this);