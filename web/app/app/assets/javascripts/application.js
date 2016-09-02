;(function(){
    angular.module('app', ['angularMoment', 'nl2br'])
}).call(window);

;(function(){
    //=require app/wall.js
}).call(window);

;(function() {

    Dropzone.autoDiscover = false;

    $('.click-once').click(function(e) {
        var btn = $(this);
        if (btn.parents('form')[0].checkValidity()) {
            btn.button('loading')
                .val('Submitting...')
                .text('Submitting...');
        }
    });

    window.init_datepicker = function() {
        // causes error in login
        if (window.app_locale) {
            $('input[data-type=date]').datetimepicker({
                format: app_locale.short_date
            });
        }
    }
    init_datepicker();

    // var search = $('#searchinput');
    // var result = $('#search-results');

    // search.blur(function() {
    //     setTimeout(function() {
    //         if (!result.is(':hidden'))
    //             result.hide();
    //         search.val('');
    //     }, 300);
    // });

    // search.keyup(function(e) {
    //     var value = search.val().trim();
    //     if (e.keyCode === 27) {
    //         search.val('');
    //         if (!result.is(':hidden'))
    //             result.hide();
    //     } else if (value.length > 3) {
    //         $.post('/ajax/quick-search', {
    //             search: value
    //         }, function(response) {
    //             result.html(response).show();
    //         });
    //     }
    // });

    if ($('.DT').length > 0) {
        $('.DT').each(function() {
            var target = $(this);
            target.DataTable({
                "ajax": '/data/' + target.data('path'),
                stateSave: true,
                "aaSorting": []
            });
        });;
    };

    // // menu begin
    // $('#cssmenu li.active').addClass('open').children('ul').show();
    // $('#cssmenu li.has-sub>a').on('click', function() {
    //     console.log('click');
    //     $(this).removeAttr('href');
    //     var element = $(this).parent('li');
    //     if (element.hasClass('open')) {
    //         element.removeClass('open');
    //         element.find('li').removeClass('open');
    //         element.find('ul').slideUp(200);
    //     } else {
    //         element.addClass('open');
    //         element.children('ul').slideDown(200);
    //         element.siblings('li').children('ul').slideUp(200);
    //         element.siblings('li').removeClass('open');
    //         element.siblings('li').find('li').removeClass('open');
    //         element.siblings('li').find('ul').slideUp(200);
    //     }
    // });
    // menu end

}).call(this);