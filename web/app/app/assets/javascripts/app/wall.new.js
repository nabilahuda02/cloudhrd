/**
*= require app/lib/backbone-diffdom
*= require app/lib/elastic
*= require app/lib/jquery-eventsource
*= require moment/moment
*= require_self
*/

function nl2br(str, is_xhtml) {
  //  discuss at: http://phpjs.org/functions/nl2br/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Philip Peterson
  // improved by: Onno Marsman
  // improved by: Atli Þór
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Maximusya
  // bugfixed by: Onno Marsman
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //    input by: Brett Zamir (http://brett-zamir.me)
  //   example 1: nl2br('Kevin\nvan\nZonneveld');
  //   returns 1: 'Kevin<br />\nvan<br />\nZonneveld'
  //   example 2: nl2br("\nOne\nTwo\n\nThree\n", false);
  //   returns 2: '<br>\nOne<br>\nTwo<br>\n<br>\nThree<br>\n'
  //   example 3: nl2br("\nOne\nTwo\n\nThree\n", true);
  //   returns 3: '<br />\nOne<br />\nTwo<br />\n<br />\nThree<br />\n'

  var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display

  return (str + '')
    .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

var shareInput = new (Backbone.View.extend({
    events : {
        'keyup textarea' : function(e) {
            if(e.keyCode === 13 && e.shiftKey && this.input.val().trim()) {
                this.submit();
                e.preventDefault();
                return false;
            }
        },
        'click #submit': function() {
            this.submit();
        }
    },
    submit: function() {
        $.post('/wall/create-share', {
            type: 'text',
            content: this.input.val().trim()
        });
        this.reset();
    },
    reset: function() {
        this.input.val('');
    },
    initialize: function() {
        this.input = $('textarea', this.$el);
        this.input.elastic();
    }
}))({
    el: '#new-share'
});

var feedModel = new (Backbone.Model.extend({
    defaults: {
        data: []
    }
}));

var feedsource;
function setEventSourceLength(len) {
    if(feedsource && feedsource.url) {
        feedsource.close();
        feedsource = null;
    }
    $.eventsource({
        url: "/wall/updates/" + len,
        dataType: "json", 
        open: function(data) {
            feedsource = data.target;
        },
        message: function(data) {
            feedModel.set('data', data);
        }
    });
}
setEventSourceLength(4);

var feedsView = new (Backbone.View.extend({
    templateData: function() {
        return this.model.toJSON();
    },
    template: _.template($('#feeds-template').text()),
    render: Backbone.VirtualDomRenderer,
    events: {
        'click .toggle-more': function(e) {
            $(e.target).parents('.comments').toggleClass('more');
            $(e.target).remove();
        },
        'keyup textarea' : function(e) {
            var target = $(e.currentTarget);
            if(e.keyCode === 13 && e.shiftKey && target.val().trim()) {
                this.submit(target.data('feedid'), target.val().trim());
                target.val('');
                e.preventDefault();
                return false;
            }
        },
        'click .remove-comment': function(e) {
            var target = $(e.currentTarget);
            bootbox.confirm('Are you sure you want to remove this comment?', function(response){
                if(response)
                    $.get('/wall/remove-comment/' + target.data('commentid'));
            });
        },
        'click .remove-share': function(e) {
            var target = $(e.currentTarget);
            bootbox.confirm('Are you sure you want to remove this share?', function(response){
                if(response)
                    $.get('/wall/remove-share/' + target.data('shareid'));
            });
        }
    },
    submit: function(id, comment) {
        $.post('/wall/create-comment/' + id, {
            comment: comment
        });
    },
    model: feedModel,
    initialize: function() {
        this.listenTo(this.model, 'change', this.render);
    }
}))({el: '#feeds .inner'});


