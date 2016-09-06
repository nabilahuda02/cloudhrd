;(function(){
    angular.module('app', ['angularMoment', 'nl2br'])

    .filter('currency', function(){
        var decimal_places = app_locale.decimal_places;
        var decimal_separator = app_locale.decimal_separator;
        var currency_symbol = app_locale.currency_symbol;
        var symbol_location = app_locale.symbol_location;
        var thousand_separator = app_locale.thousand_separator;
        function number_format(number) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimal_places) ? 0 : Math.abs(decimal_places),
                sep = (typeof thousand_separator === 'undefined') ? ',' : thousand_separator,
                dec = (typeof decimal_separator === 'undefined') ? '.' : decimal_separator,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + (Math.round(n * k) / k).toFixed(prec);
                };
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }
            return s.join(dec);
        }
        return number_format;
    })

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