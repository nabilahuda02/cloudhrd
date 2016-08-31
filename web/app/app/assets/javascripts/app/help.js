(function(window){
  if(window.Markdown) {
    var cache = {};
    var converter = new Markdown.Converter();
    var modal = $('#help-modal');
    var openModal = function(contents, title) {
      $('#help-title').text(title);
      $('#help-body').html(contents);
      modal.modal('show');
    }
    var closeModal = function() {
      modal.modal('hide');
    }
    window.HelpFile = {
      show: function(file, title) {
        var contents = '';
        if(cache[file]) {
          contents = cache[file];
          openModal(cache[file], title)
        } else {
          $.get('/help/' + file)
            .then(function(fileContents){
              cache[file] = converter.makeHtml(fileContents.replace(/\!\[\]\(images\/cloudhrd\//g, '![](/help/images/'));
              openModal(cache[file], title)
            })
        }
      },
      close: closeModal
    }
  }
})(window);
