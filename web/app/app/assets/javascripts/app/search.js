(function(){


  var search = $('#searchinput');
  var result = $('#search-results');

  // search.blur(function(){
  //   setTimeout(function() {
  //     if(!result.is(':hidden'))
  //       result.hide();
  //       search.val('');
  //   }, 300);
  // })

  search.keyup(function(e){
    var value = search.val().trim();
    if(e.keyCode === 27) {
      search.val('');
      if(!result.is(':hidden'))
        result.hide();
    } else if(value.length > 3) {
      $.post('/ajax/quick-search', {search: value}, function(response){
        result.html(response).show();
      });
    }
  });

}).call(this);