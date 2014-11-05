/**
 *= require jqueryui/ui/sortable
 *= require app/lib/elastic
 *= require app/lib/jquery-eventsource
 *= require app/x-edit-task-heading
 *= require_self
 */

// ;(function(){

    $.fn.editable.defaults.ajaxOptions = {type: "put"};

    // $('.inner-task').sortable({
    //     connectWith: '.inner-task',
    //     placeholder: 'ui-state-highlight'
    // }).disableSelection();
    $('.btn').button();

    var taskCategoriesCollection = new (Backbone.Collection.extend({
        url : '/task-categories'
    }))(taskCategories);
    var taskTagsCollection = new (Backbone.Collection.extend({
        url: '/task-tags'
    }))(taskTags);
    var tasksCollection = new(Backbone.Collection.extend({
        model: Backbone.Model.extend({
            currentTag: function() {
                var activeCategory = actionModel.get('activeCategory');
                var tag = this.get('tags').filter(function(tag){
                    return tag.tag_category_id === activeCategory;
                });
                if(tag.length > 0) {
                    return tag[0];
                }
                console.error('tag not found for categoryid ' + activeCategory)
                return {};
            },
            currentOrder: function() {
                var activeCategory = actionModel.get('activeCategory');
                var order = this.get('orders').filter(function(order){
                    return order.tag_category_id === activeCategory;
                });
                if(order.length > 0) {
                    return order[0].order;
                }
                console.error('order not found for categoryid ' + activeCategory)
                return 0;
            },
            setArchived: function() {
                this.set({
                    archived: true
                });
                $.post('/task/' + this.get('id') + '/set-archived');
            },
            unsetArchived: function() {
                $.post('/task/' + this.get('id') + '/unset-archived');
            }
        })
    }));

    $.eventsource({
        url: "/task/stream",
        dataType: "json", 
        open: function(data) {
        },
        message: function(data) {
            tasksCollection.set(data);
        }
    });

    var actionModel = new (Backbone.Model.extend({
        save: function() {
            window.localStorage.setItem('taskActionConfig', (JSON.stringify(this.toJSON())));
        },
        initialize: function() {
            taskActionConfig = JSON.parse(window.localStorage.getItem('taskActionConfig')) || {
                activeCategory: 1
            };
            this.set(taskActionConfig);
        }
    }));

    var actionView = new(Backbone.View.extend({
        collection: taskCategoriesCollection,
        model: actionModel,
        events: {
            'change #control-archived': function(e) {
                if($(e.currentTarget).is(':checked')) {
                    this.model.set({showArchived: true});
                    return;
                }
                this.model.set({showArchived: false});
            },
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
                        }, {
                            success: function(model) {
                                 this.setActiveCategory(model);
                                 window.location.reload(true);
                            }.bind(this)
                        });
                    }
                }.bind(this))
            },
            'click #del-group': function(e) {
                bootbox.confirm('Are you sure you want to remove this group?', function(res){
                    var ids = this.collection.pluck('id');
                    if(res && ids.length >= 2) {
                        var current = this.model.get('activeCategory');
                        var active = _.without(ids, current).shift();
                        var category = this.collection.get(active);
                        this.setActiveCategory(category);
                        this.collection.get(current).destroy();
                    }
                }.bind(this))
            },
            'click #new-heading': function(e) {
                bootbox.prompt('New Tag Name', function(res){
                    if(res && res.trim()) {
                        taskTagsCollection.create({
                            name: res.trim(),
                            tag_category_id: this.model.get('activeCategory'),
                            label: 'default'
                        }, {
                            success: function(model) {
                                 window.location.reload(true);
                            }.bind(this)
                        });
                    }
                }.bind(this));
            }
        },
        setActiveCategory: function(category) {
            this.model.set('activeCategory', category.id).save();
            this.$activeCategory.text(category.get('name'));
            $('.group-name', this.$el).text(category.get('name'));
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
            if(this.model.get('showArchived')) {
                $('#control-archived', this.$el).attr('checked', true);
            } else {
                $('#control-archived', this.$el).attr('checked', false);
            }
        },
        initialize: function() {
            this.$menu = $('#group-menu', this.$el);
            this.$activeCategory = $('#active-category', this.$el);
            var render = _.debounce(function(){
                this.render()
            }.bind(this), 16);
            this.listenTo(this.collection, 'all', render);
            this.listenTo(this.model, 'all', render);
            render();
        }
    }))({
        el: '#groups'
    });

    var taskHeadingsView = new(Backbone.View.extend({
        tags: taskTagsCollection,
        model: actionModel,
        template: _.template($('#task-headings-template').text()),
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
                this.taskHeadingViews.forEach(function(view, index) {
                    view.unbind();
                    view.remove();
                });
                this.$el.html(this.template());
                this.collection.each(function(model){
                    this.taskHeadingViews.push(new taskHeadingView(model));
                }.bind(this));
                $('.panels').sortable({
                    start: function(){
                        isNotDragging = false;
                    },
                    stop: function() {
                        isNotDragging = true;
                    },
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
            this.taskHeadingViews = [];
            var setCollection = _.debounce(function(){
                this.setCollection()
            }.bind(this), 16);
            this.listenTo(this.model, 'all', setCollection);
            setCollection();
        }
    }))({
        el: '#task-headings'
    });

    var tagChange = false;
    var timeout;
    var isNotDragging = true;
    var taskHeadingView = Backbone.View.extend({
        template: _.template($('#task-heading-template').text()),
        events: {
            'click .create-todo': 'create',
            // firefox fix
            'click .new-input': function(e){
                $(e.currentTarget).focus();
            },
            'keyup .new-input': function(e) {
                if(e.keyCode === 13 && e.shiftKey) {
                    this.create();
                    e.preventDefault();
                    return false;
                }
            },
            'click .editable-remove' : function(e) {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    var panel = $(e.target).parents('.panel');
                    var id = panel.data('tagid');
                    if(id) {
                        var model = taskTagsCollection.get(id);
                        bootbox.confirm('Are you sure you want to delete ' + model.get('name') + '?', function(res){
                            if(res) {
                                model.destroy();
                                panel.remove();
                                window.location.reload();
                            }
                        });
                    }
                }, 17);
            }
        },
        create: function() {
            var target = $('.new-input', this.element);
            var val = target.val().trim();
            if(val) {
                $.post('/tasks', {
                    description: val,
                    tag_id: this.model.get('id'),
                    category_id: this.model.get('tag_category_id')
                });
                target.val('');
            }
            return true;
        },
        handleTaskDrop: function(e, ui) {
            var item = $(ui.item[0]);
            var id = item.data('taskid');
            var parentTag = item.parents('.panel');
            var parentTagId = parentTag.data('tagid');
            var tag = this.model.toJSON();
            if(id && parentTagId) {
                if(tag.id !== parentTagId) {
                    var task = tasksCollection.get(id);
                    tagChange = true;
                    $.get('/tasks/' + task.id + '/set-tag/' + parentTagId, function(data){
                        $.post('/task/set-order/' + tag.tag_category_id, {
                            order: parentTag.find('li').map(function(idx, el){
                                return $(el).data('taskid');
                            }).toArray()
                        }, function(){
                            tagChange = false;
                        });
                    });
                } else if(!tagChange) {
                    $.post('/task/set-order/' + tag.tag_category_id, {
                        order: parentTag.find('li').map(function(idx, el){
                            return $(el).data('taskid');
                        }).toArray()
                    }, function(){
                        tagChange = false;
                    });
                }
            }
        },
        renderChildren: function() {
            var id = this.model.id;
            var showArchived = actionModel.get('showArchived');
            $('.inner-task', this.element).html('');
            tasksCollection.filter(function(task){
                return task.get('tags').map(function(tag){
                    return tag.id;
                }).indexOf(id) > -1;
            }).sort(function(a, b){
                return (a.currentOrder() > b.currentOrder()) ? 1 : -1;
            }).forEach(function(model){
                if(model.get('archived') && showArchived){
                    this.taskViews.push(new taskView({el : $('.inner-task', this.element), model: model}));
                } else if (model.get('archived') === 0) {
                    this.taskViews.push(new taskView({el : $('.inner-task', this.element), model: model}));
                }
            }.bind(this))
            $('.inner-task', this.element).sortable({
                start: function(){
                    isNotDragging = false;
                },
                stop: function() {
                    isNotDragging = true;
                },
                connectWith: '.inner-task',
                placeholder: 'ui-state-highlight',
                update: _.debounce(this.handleTaskDrop, 17).bind(this)
            }).disableSelection();
        },
        render: function() {
            this.element = $(this.template());
            this.element.appendTo(this.$el);
            $('.panel-title', this.element).editable();
            this.renderChildren();
        },
        initialize: function(model) {
            this.taskViews = [];
            this.model = model;
            this.$el = $('#' + this.model.get('placement'));
            this.render();
            this.listenTo(tasksCollection, 'add change remove', _.debounce(function() {
                if(isNotDragging) {
                    this.renderChildren();
                }
            }, 100).bind(this));
        }
    });
    var taskViews = {};
    var taskView = Backbone.View.extend({
        events: {
            'click .archive': function() {
                this.model.setArchived();
            },
            'click .unarchive': function() {
                this.model.unsetArchived();
            }
        },
        template: _.template($('#task-template').text()),
        render: function() {
            this.$el = $('<li data-taskid="' + this.model.get('id') + '" />').html(this.template({
                model: this.model.toJSON(), 
                currentCategoryId: actionModel.get('activeCategory')
            }));
            this.$el.appendTo(this.parent);
            $('[data-toggle=tooltip]').tooltip();
        },
        initialize: function(opt) {
            this.parent = this.$el;
            this.model = opt.model;
            this.render();
        }
    });
// }).call();