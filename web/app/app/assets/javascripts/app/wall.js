$(document).on('click', '.comment-counter', function(){
  var target = $(this);
  var timeline = $('.timeline-activity');
  var li = $(target.parents('li'));
  var input = $('.comment-input', li);
  if(timeline.hasClass('showcomment')) {
    timeline.removeClass('showcomment');
    li.removeClass('active');
  } else {
    timeline.addClass('showcomment');
    li.addClass('active');
    input.val('').focus();
  }
});
$(document).on('keyup', '.comment-input', function(e){
  var target = $(this);
  var val = target.val();
  if(e.keyCode === 13 && val.trim()) {
    var id = target.data('id');
    $.post('/ajax/update-share', {
      id: id,
      text: val.trim()
    }, function(response){
      $('#comments_' + id).prepend(response);
      $.get('/ajax/comment-count/' + id, function(num){
        $(target.parents('li').find('.comments-count-num')).text(num);
      });
    });
    target.val('');
  }
});
$(document).on('click', '.delete_comment', function(e){
  var target = $(e.currentTarget);
  var id = target.data('id');
  var parent = target.data('parent');
  $.ajax('/ajax/comment/' + id, {
    method: 'DELETE'
  }).success(function(){
    $(target.parents('.comment-wrapper')).remove();
    $.get('/ajax/comment-count/' + parent, function(num){
      $('.comments-count-num', $('[data-id='+ parent +']')).text(num);
    })
  })
});
$(document).on('click', '.share-pinned', function(e){
  var target = $(this);
  var id = target.data('id');
  if(target.hasClass('active')) {
    return $.get('/ajax/bulletin-pinned/0/' + id, function(){
      window.location.reload(true);
    })
  }
  $.get('/ajax/bulletin-pinned/1/' + id, function(){
    window.location.reload(true);
  });
});
$(document).on('click', '.send-email', function(e){
  var target = $(this);
  var id = target.data('id');

  if(confirm('Are you sure you want to email this to everyone?')) {
    target.html('Sending... ');
    $.get('/ajax/bulletin-email/' + id, function(){

      window.location.reload(true);
    });
  }
});
$('.donut-charts .widget-body')
  .height($('.donut-charts').width())
  .each(function(){
    var target = $(this);
    $.get('/ajax/entitlement/' + target.data('path'), function(response){
      target.data({morris: Morris.Donut({
          element: target[0].id,
          data: [
            {label: "Utilized", value: response.utilized},
            {label: "Balance", value: response.balance}
          ],
          colors: response.colors,
        })
      });
    });
  });

var lazyLayout = _.debounce(function(){
  $('.donut-charts .widget-body').each(function(){
    var target = $(this)
    target
      .height($('.donut-charts').width())
      .data('morris')
      .resizeHandler();
  });
}, 150);
$(window).on('resize', lazyLayout);




