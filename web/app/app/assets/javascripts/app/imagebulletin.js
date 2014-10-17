;(function(){
  var button = $('#shareBulletin');
  var imageInput = $('#imageinput');
  var textarea = $('#shareimage');

  imageInput.on('change', function(e){
    if(e.target.files.length) {
      button.removeAttr('disabled');
      textarea.attr('placeholder', 'Describe the image...').focus();
    } else {
      button.attr('disabled', true);
      textarea.attr('placeholder', 'Choose an image to share...');
    }
  })

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target);
    if(target.hasClass('picture')) {
      imageInput.wrap('<form>').closest('form').get(0).reset();
      imageInput.unwrap();
      button.attr('disabled', true);
      textarea.attr('placeholder', 'Choose an image to share...');
    }
  })
  button.attr('disabled', '');

  $('.fancybox-link').click(function(e){
    var target = $(this);
    bootbox.alert('<img style="width:100%" src="' + target.attr('href') + '"> <div><br />' + target.attr('title') + '</div>', function(){});
    e.preventDefault();
    return false;
  });

  // $('body').on('click', '.confirmdelete', function(e){
  //   if(!confirm('Are you sure you want to remove this?')) {
  //     e.preventDefault();
  //     return false;
  //   }
  // })
}).call(this);