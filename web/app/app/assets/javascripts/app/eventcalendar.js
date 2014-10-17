;(function(){

  var calBody = $('#calendarContent');
  var calUl = $('#customCalendar');

  $.getJSON('/ajax/upcomming', function(events){
    if(events.length === 0) {
      calBody.html('<p class="colorWhite text-center strong">No upcomming events</p>');
      return;
    }
    events.forEach(function(event, idx){
      var li = $('<li><a href="javascript:;">' + (idx + 1) + '</a></li>');
      li.click(function(e){
        calBody.html('<p class="colorWhite text-center strong ">' + event.title + '</p><p class="colorWhite text-center fs11"> ' + event.event_date + ' </p>');
        $('li', calUl).removeClass('active');
        $(this).addClass('active');
      });
      calUl.append(li);
    });
    $('li', calUl).first().click();
  });


}).call(this);