/**
 *= require jqueryui/ui/sortable
 *= require app/lib/elastic
 *= require_self
 */

// ;(function(){

    $.fn.editable.defaults.ajaxOptions = {type: "put"};

    $('.inner-task').sortable({
        connectWith: '.inner-task',
        placeholder: 'ui-state-highlight'
    }).disableSelection();
    $('.btn').button();

    var actionModel = new (Backbone.Model.extend({
        save: function() {
            window.localStorage.setItem('taskActionConfig', (JSON.stringify(this.toJSON())));
        },
        initialize: function() {
            var taskActionConfig = {
                activeCategory: null,
                includeArchived: false
            };
            try {
                taskActionConfig = JSON.parse(window.localStorage.getItem('taskActionConfig'));
            } catch (e) {}
            this.set(taskActionConfig);
        }
    }));

    var taskCategoriesCollection = new (Backbone.Collection.extend({
        url : '/task-categories'
    }))(taskCategories);
    var taskTagsCollection = new (Backbone.Collection.extend({
        url: '/task-tags'
    }))(taskTags);

    var actionView = new(Backbone.View.extend({
        collection: taskCategoriesCollection,
        model: actionModel,
        events: {
            'click [data-categoryid]': function(e) {
                var categoryId = $(e.currentTarget).data('categoryid');
                var category = this.collection.get(categoryId);
                if(category) {
                    this.setActiveCategory(category);
                }
            },
            'click #new-group': function(e) {
                bootbox.prompt('New Group Name', function(res){
                    if(res && res.trim()) {
                        this.collection.create({
                            name: res.trim()
                        });
                    }
                }.bind(this))
            },
        },
        setActiveCategory: function(category) {
            this.model.set('activeCategory', category.id).save();
            this.$activeCategory.text(category.get('name'));
        },
        render: function() {
            this.$menu.html('');
            var activeCategory = this.model.get('activeCategory');
            this.collection.each(function(category){
                if(category.id === activeCategory) {
                    this.setActiveCategory(category);
                }
                this.$menu.append('<li data-categoryid="' + category.id + '"><a>' + category.get('name') + '</a></li>');
            }.bind(this));
        },
        initialize: function() {
            this.$menu = $('#group-menu', this.$el);
            this.$activeCategory = $('#active-category', this.$el);
            var render = _.debounce(function(){
                this.render()
            }.bind(this), 16);
            this.listenTo(this.collection, 'all', render);
            render();
        }
    }))({
        el: '#groups'
    });

    var taskHeadingView = Backbone.View.extend({
        template: _.template($('#task-heading-template').text()),
        render: function() {
            this.$el.append(this.template());
            $('.panel-title', this.$el).editable();
        },
        initialize: function(model) {
            this.model = model;
            this.$el = $('#' + this.model.get('placement'));
            this.render();
        }
    })

    var taskHeadingsView = new(Backbone.View.extend({
        tags: taskTagsCollection,
        model: actionModel,
        template: _.template($('#task-headings-template').text()),
        events: {
            'click .create-todo': function() {
                console.log(this)
            }
        },
        handleTagDrop: _.debounce(function(e, ui) {
                /*
                    1. get original tag
                    2. check leftright
                    3. check order
                    4. update if neccessary
                 */
                var item = $(ui.item[0]);
                var id = item.data('tagid');
                if(id) {
                    var tag = this.collection.get(id);
                    var parentId = item.parents('.panels').attr('id');
                    var order = $('.panel').index(item);
                    if(tag.get('placement') !== parentId) {
                        tag.set({'placement': parentId}).save();
                    }
                    if(tag.get('order') !== order) {
                        $('.panel').each(function(index, panel){
                            this.collection.get($(panel).data('tagid')).set({
                                order: index
                            }).save()
                        }.bind(this))
                    }
                }
            }, 16),
        render: function() {
            if(this.collection) {
                this.$el.html(this.template());
                this.collection.each(function(model){
                    new taskHeadingView(model)
                });
                $('.panels').sortable({
                    connectWith: '.panels',
                    placeholder: 'ui-state-highlight',
                    handle: '.panel-heading',
                    update: this.handleTagDrop.bind(this)
                }).disableSelection();
            }
        },
        setCollection: function() {
            var parent = this;
            this.collection = new (Backbone.Collection.extend({
                comparator: function(a, b) {
                    return a.get('order') > b.get('order') ? 1 : -1;
                },
                initialize: function() {
                    setTimeout(function() {
                        parent.render();
                    }, 1);
                    return this;
                }
            }))(taskTagsCollection.where({'tag_category_id': this.model.get('activeCategory')}));
        },
        initialize: function() {
            var setCollection = _.debounce(function(){
                this.setCollection()
            }.bind(this), 16);
            this.listenTo(this.model, 'all', setCollection);
            setCollection();
        }
    }))({
        el: '#task-headings'
    });

    var taskView = Backbone.View.extend({
        
    });


// }).call();