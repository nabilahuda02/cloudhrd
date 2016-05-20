$('.donut-charts .widget-body')
  .height($('.donut-charts .widget-body').width())
  .each(function(){
    var target = $(this);
    var parent = target.parents('.donut-charts');
    $.get('/ajax/entitlement/' + target.data('path'), function(response){
      $('.utilized', parent).css({
        'background-color': response.colors[0],
        color: 'white'
      });
      $('.balance', parent).css({
        'background-color': response.colors[1],
        color: 'white'
      });
      var formatter = function(value){ return value };
      if(target.data('path').indexOf('medical') > -1) {
        $('.entitlement > div', parent).text(currency_format(response.entitlement));
        $('.utilized > div', parent).text(currency_format(response.utilized));
        $('.balance > div', parent).text(currency_format(response.balance));
        formatter = function(value) {
          return currency_format(value);
        }
      } else {
        $('.entitlement > div', parent).text(response.entitlement);
        $('.utilized > div', parent).text(response.utilized);
        $('.balance > div', parent).text(response.balance);
      }
      Morris.Donut({
        element: target[0].id,
        data: [
          {label: "Utilized", value: response.utilized},
          {label: "Balance", value: response.balance}
        ],
        formatter: formatter,
        colors: response.colors
      });
      $('.legend-inner').fadeIn();
    });
  });