var tbl;
$('.DT').each(function(){
  var target = $(this);
  tbl = target.DataTable({
    "ajax": '/data/' + target.data('path'),
    "aaSorting": []
  });
});