/**
 *= require jquery/dist/jquery
 *= require bootstrap/dist/js/bootstrap
 *= require bootbox/bootbox
 *= require_self
 */
;(function(){
  $(document).on('click', '.confirm-delete', function(e){
    e.preventDefault();
    var form = $(this).parents('form')[0];
    bootbox.confirm('Are your sure you want to delete this?', function(res){
      if(res) {
        form.submit();
      }
    });
    return false;
  });

  $.notify = function(message, type) {
    var notification = '<div class="alert alert-' + type + ' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' + message + '</div>';
    setTimeout(function() {
      notification.remove();
    }, 3000);
    $('#notification-wrapper').append(notification);
  }

}).call(this);