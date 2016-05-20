
@extends('layouts.module')
@section('content')
  <div class="col-md-12">
    <div class="col-md-12" id="calendar">
    </div>
  </div>
@stop
@section('script')
  <script>
      $('#calendar').fullCalendar({
        eventSources: [
          {
            url : '/wall/event-leaves-annual',
            color: '#4a33d7',
            textColor: '#fff'
          },
          {
            url : '/wall/event-leaves-medical',
            color: '#9c33d7',
            textColor: '#fff'
          },
          {
            url : '/wall/event-shares',
            color: '#fdd53c',
            textColor: '#222'
          }
        ],
        eventClick: function(calEvent) {
          var type = calEvent.className.pop();
          var target = $(this);
          var id = calEvent.id;
          if(type === 'wall-share') {
            $.getJSON('/shares/' + id, function(data){
              target.popover({
                placement: 'top',
                title: '<i class="fa fa-bullhorn"></i> ' + calEvent.title,
                html: true,
                content: '<div>' + data.content + '</div><div><b>' + data.event_date + '</b></div>'
              }).popover('show');
            });
          } else if(type === 'leaves') {
            $.getJSON('/wall/leave-details/' + id, function(data){
              target.popover({
                placement: 'top',
                title: '<i class="fa fa-plane"></i> ' + calEvent.title,
                html: true,
                content: '<div><b>' + data.leave.type.name + '</b></div>'
              }).popover('show');
            });
          }
        }
      });
  </script>
@stop
@section('style')
  <style>
    #calendar {
      padding-top: 16px;
    }
  </style>
@stop
