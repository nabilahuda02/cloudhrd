/**
*= require app/lib/backbone-diffdom
*= require app/lib/elastic
*= require app/lib/jquery-eventsource
*= require moment/moment
*= require_self
*/
function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
    return (str + '')
    .replace(/(<([^>]+)>)/ig,"")
    .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
;(function(){
    var hidden, visibilityChange; 
    if (typeof document.hidden !== "undefined") {
        hidden = "hidden";
        visibilityChange = "visibilitychange";
    } else if (typeof document.mozHidden !== "undefined") {
        hidden = "mozHidden";
        visibilityChange = "mozvisibilitychange";
    } else if (typeof document.msHidden !== "undefined") {
        hidden = "msHidden";
        visibilityChange = "msvisibilitychange";
    } else if (typeof document.webkitHidden !== "undefined") {
        hidden = "webkitHidden";
        visibilityChange = "webkitvisibilitychange";
    }
    var videoElement = document.getElementById("videoElement");
    var pageIsShown = true;
    function handleVisibilityChange() {
        if (document[hidden]) {
            pageIsShown = false;
        } else {
            pageIsShown = true;
        }
    }
    var audioAlert = $('<audio id="soundHandle" style="display:none;"></audio>').attr('src','/alert.mp3')[0];
    if (typeof document.addEventListener === "undefined" || 
        typeof document[hidden] === "undefined") {
    } else {
        document.addEventListener(visibilityChange, handleVisibilityChange, false);
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
    var sharesCollection = new Backbone.Collection();
    var commentsCollection = new Backbone.Collection();
    var feedsources = [];
    var currentLength = 4;
    var setEventSourceLength = _.debounce(function(len) {
        $('#load-older').button('loading');
        currentLength = len;
        if(feedsources[0] && feedsources[0].url) {
            feedsources[0].close();
            feedsources[0] = null;
        }
        if(feedsources[1] && feedsources[1].url) {
            feedsources[1].close();
            feedsources[1] = null;
        }
        $.eventsource({
            url: "/wall/shares/" + len,
            dataType: "json", 
            open: function(data) {
                feedsources[0] = data.target;
            },
            message: function(data) {
                if(!pageIsShown)
                    audioAlert.play();
                sharesCollection.set(data);
            }
        });
        $.eventsource({
            url: "/wall/comments/" + len,
            dataType: "json", 
            open: function(data) {
                feedsources[1] = data.target;
            },
            message: function(data) {
                if(!pageIsShown)
                    audioAlert.play();
                commentsCollection.set(data);
            }
        });
    }, 500);
    setEventSourceLength(5);
    var CommentsView = Backbone.View.extend({
        events: {
            'click .toggle-more': function(e) {
                this.opened = true;
                this.comments.toggleClass('more');
                $(e.target).remove();
            },
            'click .remove-comment': function(e) {
                var target = $(e.currentTarget);
                bootbox.confirm('Are you sure you want to remove this comment?', function(response){
                    if(response)
                        $.get('/wall/remove-comment/' + target.data('commentid'));
                });
            },
        },
        collection: commentsCollection,
        template: _.template($('#comments-template').text()),
        templateData: function() {
            return {
                comments: this.collection.toJSON().filter(function(comment){
                    return comment.share_id === this.collectionId;
                }.bind(this)),
                opened: this.opened
            };
        },
        render: Backbone.VirtualDomRenderer,
        initialize: function(opt) {
            this.opened = false;
            this.oldData = '{}';
            this.comments = this.$el.parents('.comments');
            this.collectionId = opt.collectionId;
            this.listenTo(this.collection, 'all', function(){
                var templateData = this.templateData();
                if(JSON.stringify(templateData) != this.oldData) {
                    this.oldData = JSON.stringify(templateData);
                    this.render();
                }
            }.bind(this));
            this.on('virtualdomrenderer:rendered', function(){
                if(!this.opened) {
                    if(this.templateData().comments.length > 2) {
                        this.comments.removeClass('less-than-three-comments');
                    } else {
                        this.comments.addClass('less-than-three-comments');
                    }
                }
            });
            this.render();
        }
    });
    var feedsView = new(Backbone.View.extend({
        events: {
            'click #load-older': function(e) {
                setEventSourceLength(currentLength + 5);
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
            'click .remove-share': function(e) {
                var target = $(e.currentTarget);
                bootbox.confirm('Are you sure you want to remove this share?', function(response){
                    if(response)
                        $.get('/wall/remove-share/' + target.data('shareid'));
                });
            },
            'click .pin-share': function(e) {
                var target = $('.fa', e.currentTarget);
                if(target.hasClass('active')) {
                    target.removeClass('active');
                    $.get('/wall/unset-pin/' + target.data('shareid'));
                } else {
                    target.addClass('active');                
                    $.get('/wall/set-pin/' + target.data('shareid'));    
                }
            },
        },
        submit: function(id, comment) {
            $.post('/wall/create-comment/' + id, {
                comment: comment
            });
        },
        collection: sharesCollection,
        template: _.template($('#feeds-template').text()),
        templateData: function() {
            return {
                shares : this.collection.toJSON()
            };
        },
        indexes: '',
        children: [],
        rendered: null,
        render: Backbone.VirtualDomRenderer,
        initialize: function() {
            this.listenTo(this.collection, 'all', function(){
                if(JSON.stringify(this.collection.pluck('id')) !== this.indexes ) {
                    this.indexes = JSON.stringify(this.collection.pluck('id'));
                    this.render();
                }
            }.bind(this));
            this.on('virtualdomrenderer:rendered', function() {
                this.children.forEach(function(child, index){
                    child.remove();
                    delete this.children[index]
                }.bind(this));
                this.rendered = $(this.template(this.templateData()));
                this.$el.html(this.rendered);
                this.rendered.find('.comments-view').each(function(index, div){
                    this.children.push(new CommentsView({el : div, collectionId: $(div).data('shareid')}));
                }.bind(this));
            });
            this.render();
        }
    }))({el: '#feeds .inner'});
}.call(this));