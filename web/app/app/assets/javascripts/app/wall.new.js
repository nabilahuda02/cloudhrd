/**
*= require app/lib/backbone-diffdom
*= require app/lib/elastic
*= require app/lib/jquery-eventsource
*= require moment/moment
*= require_self
*/


    // $('html, body').animate({
    //     scrollTop: $("#elementtoScrollToID").offset().top
    // }, 2000);

var array_diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};

function nl2br(str, is_xhtml) {

  var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display

  return (str + '')
    .replace(/(<([^>]+)>)/ig,"")
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

var doRender = _.debounce(function(){
    console.log('called')
    feedsView.trigger('dorender');
}, 17);

var feedCollection = new (Backbone.Collection.extend({
    initialize: function() {
        this.on('add change remove', function(){
            doRender();
        });
    }
}))

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
            feedCollection.set(data);
        }
    });
}
setEventSourceLength(4);

var feedsView = new (Backbone.View.extend({
    oldTemplateData: '',
    templateData: function() {
        return feedCollection.toJSON().map(function(model){
            return model.id
        });
    },
    template: _.template($('#feeds-view-template').text()),
    render: function(){
        if(JSON.stringify(this.templateData()) !== this.oldTemplateData) {
            if(this.firstRun) {
                this.$el.html(this.template(this.templateData()));
            }
            this.createChildren();
            this.oldTemplateData = JSON.stringify(this.templateData());
            this.firstRun = false;
        }
    },
    events: {
        'click .remove-share': function(e) {
            var target = $(e.currentTarget);
            bootbox.confirm('Are you sure you want to remove this share?', function(response){
                if(response)
                    $.get('/wall/remove-share/' + target.data('shareid'));
            });
        }
    //     'click .toggle-more': function(e) {
    //         $(e.target).parents('.comments').toggleClass('more');
    //         $(e.target).remove();
    //     },
    //     'keyup textarea' : function(e) {
    //         var target = $(e.currentTarget);
    //         if(e.keyCode === 13 && e.shiftKey && target.val().trim()) {
    //             this.submit(target.data('feedid'), target.val().trim());
    //             target.val('');
    //             e.preventDefault();
    //             return false;
    //         }
    //     },
    //     'click .remove-comment': function(e) {
    //         var target = $(e.currentTarget);
    //         bootbox.confirm('Are you sure you want to remove this comment?', function(response){
    //             if(response)
    //                 $.get('/wall/remove-comment/' + target.data('commentid'));
    //         });
    //     },
    },
    // submit: function(id, comment) {
    //     $.post('/wall/create-comment/' + id, {
    //         comment: comment
    //     });
    // },
    createChildren: function() {
        feedCollection.toArray().forEach(function(model){
            if(!this.childViews[model.id]) {
                var div = $('<div />');
                if(this.firstRun) {
                    div.appendTo($('.feeds-wrapper-outlet', this.$el));
                } else {
                    div.prependTo($('.feeds-wrapper-outlet', this.$el));
                }
                this.childViews[model.id] = new FeedWrapperView({el: div, model: model});
            }
        }.bind(this));
        if(!this.firstRun) {
            var diff = array_diff.call(JSON.parse(this.oldTemplateData), this.templateData());
            diff.forEach(function(id){
                if(this.childViews[id]) {
                    this.childViews[id].remove();
                    delete this.childViews[id];
                }
            }.bind(this))
        }
    },
    firstRun: true,
    childViews: {},
    initialize: function() {
        this.on('dorender', function(){
            this.render();
            _.each(this.childViews, function(view){
                view.trigger('dorender')
            });
        });
    }
}))({el: '#feeds .inner'});

var FeedWrapperView = Backbone.View.extend({
    oldTemplateData: '',
    templateData: function() {
        return {};
    },
    template: _.template($('#feeds-wrapper-view-template').text()),
    render: function(){
        if(JSON.stringify(this.templateData()) !== this.oldTemplateData) {
            this.$el.append(this.template(this.templateData()));
            this.renderFeedView();
            this.renderCommentsView();
            this.oldTemplateData = JSON.stringify(this.templateData());
        }
    },
    renderFeedView: function() {
        var modelData = this.model.toJSON();
        delete modelData.comments;
        this.childViews.feed = new FeedView({model: modelData, el: $('.feed-view-outlet', this.$el)});
    },
    renderCommentsView: function() {
        var modelData = this.model.toJSON();
    },
    childViews: {},
    initialize: function() {
        this.on('dorender', function(){
            this.render();
            _.each(this.childViews, function(view){
                view.trigger('dorender')
            });
        });
        this.render();
    }
});

var FeedView = Backbone.View.extend({
    oldTemplateData: '',
    templateData: function() {
        return {share: this.model};
    },
    template: _.template($('#feed-view-template').text()),
    render: function(){
        if(JSON.stringify(this.templateData()) !== this.oldTemplateData) {
            this.$el.html(this.template(this.templateData()));
            this.oldTemplateData = JSON.stringify(this.templateData());
        }
    },
    initialize: function() {
        this.on('dorender', function(){
            this.render();
            _.each(this.childViews, function(view){
                view.trigger('dorender')
            });
        });
        this.render();
    }

});