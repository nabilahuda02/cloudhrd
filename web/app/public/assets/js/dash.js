;(function(){

  $('.chart').each(function(){
    var target = $(this);
    target.height(target.width());
    if(target.hasClass('entitlement')) {
      $.get('/ajax/entitlement/' + target.data('path'), function(response){
        Morris.Donut({
          element: target[0].id,
          data: [
            {label: "Utilized", value: response.utilized},
            {label: "Balance", value: response.balance}
          ],
          colors: response.colors
        });
      });
    } else if(target.hasClass('status')) {
      $.get('/ajax/status/' + target.data('path'), function(response){

        var data = {
          'Pending': 0,
          'Verified': 0,
          'Approved': 0,
          'Rejected': 0,
          'Cancelled': 0,
        };
        response.forEach(function(res){
          data[res.name] = res.count;
        });

        var data_array = [];
        $.each(data, function(index, value){
          data_array.push({
            label: index,
            value: value
          });
        });
        Morris.Donut({
          element: target[0].id,
          data: data_array,
          colors: ['#3bafda', '#967adc', '#8cc152', '#ff625f', '#f39c30']
        });
      });
    } else {

      Morris.Bar({
        element: target[0].id,
        data: [
          { y: '2006', a: 100},
          { y: '2007', a: 75},
          { y: '2008', a: 50},
          { y: '2009', a: 75},
          { y: '2010', a: 50},
        ],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Approved Leaves']
      });
    }
  })


}).call(this);