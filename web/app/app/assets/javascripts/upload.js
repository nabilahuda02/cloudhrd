Dropzone.autoDiscover = false;
function attachUploader() {
    var path = $('div#upload').data('path');
    var type = $('div#upload').data('type');
    new Dropzone("div#upload", {
        url: '/upload/do/' + path,
        maxFilesize: 10,
        addRemoveLinks: true,
        acceptedFiles: type || 'image/*',
        init: function() {
            this.on('success', function(File, response) {
                File.response = response;
            });
            this.on('removedfile', function(File) {
                var response = File.response;
                if (response) {
                    $.get('/upload/remove/' + response.mask);
                }
            });
            this.on('error', function(){
                console.log(arguments)
            })
        }
    });
}

if ($('div#upload').length > 0) {
    attachUploader();
}

$('.remove_uploaded').click(function(e){
    var target = $(this);
    var li = target.parent();
    var id = target.data('id');
    e.preventDefault();
    bootbox.confirm('Remove attached file?', function(result){
        if(result) {
            $.get('/upload/remove/' + id, function(){
                li.remove();
            });
        }
    });
    return false;
});

$('.view_uploaded').click(function(){
    var target = $(this);
    var url = target.data('url');
    bootbox.alert('<img src="'+ url +'" />', function(){}).addClass('upload-preview');
});