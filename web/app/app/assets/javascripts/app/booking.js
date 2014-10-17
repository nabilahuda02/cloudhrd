;(function(){
  var date = $('#booking_date');
  var slots = $('#slots');
  var room = $('#room_booking_room_id');

  $('#slots,#booking_date').change(function() {
    room.empty();
    var dateVal = date.val();
    var slotsVal = slots.val();
    if(dateVal && slotsVal) {
      $.post('/ajax/available-rooms', {
        slots: slotsVal,
        date: dateVal
      }, function(rooms){
        if(rooms.length === 0)
          return room.append('<option value="">No rooms available</option>');
        rooms.forEach(function(available){
          room.append('<option value="' + available.id + '">' + available.name + '</option>');
        });
      }).error(function(){
        return room.append('<option value="">Error getting rooms</option>');
      })
    }
  });
}).call(this);