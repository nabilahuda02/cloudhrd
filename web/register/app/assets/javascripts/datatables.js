/*
*= require datatables/media/js/jquery.dataTables
*= require datatables-bootstrap/dist/dataTables.bootstrap
*= require_self
*/
$('.DT').each(function(){
    var selected = jQuery.fn.dataTable.selected;
    var target = $(this);
    var path = target.data('path');
    var query = target.data('query') || {};
    var DT = target.DataTable({
        ajax: {
            url: path,
            data: query
        }
    });
    // target.on('click', 'tr', function(){
    //     var id = this.id;
    //     var index = $.inArray(id, selected);
    //     if ( index === -1 ) {
    //         selected.push( id );
    //     } else {
    //         selected.splice( index, 1 );
    //     }
    //     $(this).toggleClass('selected');
    // });
});