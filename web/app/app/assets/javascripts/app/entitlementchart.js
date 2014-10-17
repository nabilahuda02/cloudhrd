$('.donut-charts .chart-legend')
  .height($('.donut-charts .widget-body').width());
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
      $('.entitlement > div', parent).text(response.entitlement);
      $('.utilized > div', parent).text(response.utilized);
      $('.balance > div', parent).text(response.balance);
      Morris.Donut({
        element: target[0].id,
        data: [
          {label: "Utilized", value: response.utilized},
          {label: "Balance", value: response.balance}
        ],
        colors: response.colors
      });
      $('.legend-inner').fadeIn();
    });
  });