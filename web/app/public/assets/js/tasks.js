//=require ../packages/jqueryui/ui/sortable.js
//=require ../packages/autosize/dist/autosize.min.js
//=require ../packages/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js
//=require app/x-edit-task-heading.js


function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
    return (str + '')
        .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function getNotDone(items) {
    return _.where(items, {is_done: 0});
}

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
            url: function() {
                return '/tasks/' + this.id
            },
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

    // $.eventsource({
    //     url: "/task/stream",
    //     dataType: "json", 
    //     open: function(data) {
    //     },
    //     message: function(data) {
    //         tasksCollection.set(data);
    //     }
    // });

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
                if(a.get('archived')) {
                    return 1;
                }
                if(b.get('archived')) {
                    return -1;
                }
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
            if(is_admin)
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
            'click .category-label': function(e) {
                var category = taskCategoriesCollection.get($(e.currentTarget).data('categoryid'));
                actionView.setActiveCategory(category);
            },
            'click img' : function(e) {
                TaskInfoModalView.setId(this.model.id);
            },
            'click .archive': function() {
                this.model.setArchived();
            },
            'click .unarchive': function() {
                this.model.unsetArchived();
            },
            'click .delete': function() {
                bootbox.confirm('Are you sure you want to delete this task?', function(res){
                    if(res) {
                        this.model.destroy();
                    }
                }.bind(this))
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

    var TaskInfoModalView = new(Backbone.View.extend({
        id: null,
        data: {},
        followerTemplate: _.template(' <li class="is-follower" data-toggle="tooltip" title="<%-first_name%> <%-last_name%>" data-userid="<%-user_id%>"> <a href="#" class="user-avatar"><img width="100%" src="<%-(user_image) ? user_image.replace("original", "avatar") : "/images/user.jpg"%>"></a> </li> '),
        subtaskTemplate: _.template(' <li class="list-group-item <%if(is_done){%>is_done<%}%>"> <%if(is_done){%><button class="btn btn-danger btn-sm pull-right delete-subtask" data-subtaskid="<%-id%>">×</button><%}%> <label><input type="checkbox" data-subtaskid="<%-id%>" <%if(is_done){%>checked<%}%> class="subtask-set-done"> <%-name%></label></li> '),
        events: {
            'click .is-follower': function(e) {
                var target = $(e.currentTarget);
                if($('.is-follower').index(target[0]) > 0) {
                    bootbox.confirm('Are you sure you want to remove this follower?', function(res){
                        if(res) {
                            $.get('/tasks/' + this.id + '/remove-follower/' + target.data('userid'), function(){
                                this.reload();
                            }.bind(this))
                        }
                    }.bind(this))
                }
            },
            'click .delete-subtask': function(e) {
                bootbox.confirm('Are you sure you want to delete this subtask?', function(res){
                    if(res) {
                        $.ajax({
                            method: 'DELETE',
                            url: '/subtasks/' + $(e.target).data('subtaskid')
                        }).success(function(){
                            this.reload();
                        }.bind(this))
                    }
                }.bind(this));
            },
            'change .subtask-set-done': function(e) {
                var target = $(e.currentTarget);
                if(target.is(':checked')) {
                    $.get('/subtasks/' + target.data('subtaskid') + '/set-done', function(){
                        this.reload();
                    }.bind(this));
                } else {
                    $.get('/subtasks/' + target.data('subtaskid') + '/set-undone', function(){
                        this.reload();
                    }.bind(this));
                }
            },
            'keyup #new-subtask': function(e) {
                var target = $(e.currentTarget);
                var val = target.val().trim();
                if(e.keyCode === 13 && e.shiftKey && val) {
                    $.post('/subtasks', {
                        todo_id: this.id,
                        name: val
                    }, function(){
                        this.reload();
                    }.bind(this));
                    target.val('');
                }
            },
            'click .delete-task': function() {
                bootbox.confirm('Are you sure you want to delete this task?', function(res){
                    var task = tasksCollection.get(this.id);
                    if(res && task) {
                        task.destroy();
                        this.$el.modal('hide');
                        $.notify('Task deleted.', 'success');
                    }
                }.bind(this))
            },
            'click .archive-task': function() {
                var task = tasksCollection.get(this.id);
                if(task) {
                    task.set({archived: 1}).save();
                    this.reload();
                }
            },
            'click .unarchive-task': function() {
                var task = tasksCollection.get(this.id);
                if(task) {
                    task.set({archived: 0}).save();
                    this.reload();
                }
            }
        },
        setId: function(id) {
            this.id = id;
            TaskNoteView.setId(id);
            this.reload();
        },
        populate: function() {
            $('.is-follower', this.el).remove();
            this.data.followers.forEach(function(follower){
                if(follower.id !== this.data.owner.id)
                    $('.followers', this.el).prepend(this.followerTemplate(follower.profile));
            }.bind(this));
            var first;
            $('.followers', this.el)
                .prepend(this.followerTemplate(this.data.owner.profile))
                .sortable({
                    start: function(e, ui) {
                        first = $('.is-follower').first()[0];
                    },
                    update: function(e, ui) {
                        if($('.is-follower').first()[0] !== first) {
                            $.get('/tasks/' + this.id + '/set-owner/' + $('.is-follower').first().data('userid'), function(){
                                // this.reload();
                            }.bind(this))
                        }
                    }.bind(this)
                });
            $('#subtasks-list').html('');
            this.data.subtasks.sort(function(a, b){
                if(a.is_done) {
                    return -1;
                }
                if(b.is_done) {
                    return 1;
                }
                return (new Date(a.created_at).getTime() > new Date(b.created_at).getTime()) ? 1 : -1;
            }).forEach(function(subtask){
                $('#subtasks-list').prepend(this.subtaskTemplate(subtask))
            }.bind(this))
            $('.unarchive-task,.archive-task').hide();
            if(this.data.archived) {
                $('.unarchive-task').show();
            } else {
                $('.archive-task').show();
            }
            $('.modal-title')
            .editable('destroy')
            .text(this.data.description)
            .data('value', this.data.description)
            .editable({
                value: this.data.description,
                success: function(xhr, val) {
                    var task = tasksCollection.get(this.id);
                    var val = val.trim();
                    if(task && val) {
                        task.set({description: val}).save();
                        this.reload();
                    }
                }.bind(this)
            });
            $('[data-toggle=tooltip]').tooltip();
            this.show();
        },
        reload: function() {
            $.get('/taskinfo/' + this.id, function(task){
                this.data = task;
                this.populate();
            }.bind(this));
        },
        show: function() {
            this.$el.modal('show');
        },
        initialize: function() {
            $('#add-follower').editable({
                display: function() {
                    return '+';
                },
                success: function(response, value) {
                    $.get('/tasks/' + this.id + '/add-follower/' + value, function(){
                        this.reload();
                    }.bind(this))
                }.bind(this)
            });
        }
    }))({
        el: '#task-info'
    });

    var format_date = function(date) {
        return $.datepicker.formatDate(app_locale.long_date, new Date(date)) + ' @ ' + date.split(' ').pop();
    }

    var TaskNoteView = new(Backbone.View.extend({
        id: null,
        data: [],
        isEditing: false,
        activeNoteTemplate: _.template('<div class="panel-heading"><h3 class="panel-title"><%-format_date(updated_at)%></h3></div><div class="panel-body"><%=nl2br(note)%></div><div class="panel-footer text-right"><a class="btn btn-primary btn-sm" id="edit-note" data-noteid="<%-id%>">Edit</a> <a class="btn btn-danger btn-sm" data-noteid="<%-id%>" id="delete-note">Delete</a></div>'),
        noteLiTemplate: _.template('<li data-noteid="<%-id%>"><a href="#" data-toggle="tooltip" data-title=""><%-note.substr(0, 20)%></a></li>'),
        events: {
            'keyup #note-content': function(e) {
                if(e.keyCode === 13 && e.shiftKey) {
                    this.saveNote();
                }
            },
            'click #add-new-note': function() {
                this.isEditing = false;
                $('#active-note').hide();
                $('#new-note').show();
                $('#note-content').val('').focus();
                $('.note-titles li').removeClass('active');
                $('#note-input-title').text('New Note');
            },
            'click #save-new-note': 'saveNote',
            'click .note-titles li' : function(e) {
                var target = $(e.currentTarget);
                this.showNote(target.data('noteid'));
            },
            'click #delete-note': function(e) {
                var target = $(e.currentTarget);
                bootbox.confirm('Are you sure you want to delete this note?', function(res){
                    if(res) {
                        $.ajax({
                            method: 'DELETE',
                            url: '/tasks/' + target.data('noteid') + '/notes'
                        }).success(function(){
                            this.reload();
                        }.bind(this));
                    }
                }.bind(this))
            },
            'click #edit-note': function(e) {
                var target = $(e.currentTarget);
                var activeNote = _.findWhere(this.data, {
                    id: target.data('noteid')
                });
                if(activeNote) {
                    this.isEditing = activeNote.id;
                    $('#note-input-title').text('Editing');
                    $('#note-content').val(activeNote.note);
                    $('#active-note').hide();
                    $('#new-note').show();
                    $('#note-content').focus();
                }
            }
        },
        saveNote: function() {
            var val = $('#note-content').val().trim();
            if(val) {
                if(!this.isEditing) {
                    $.post('/tasks/'+ this.id +'/notes', {
                        note: val
                    }, function(data){
                        this.reload();
                    }.bind(this));
                    $('#note-content').val('');
                } else {
                    $.ajax({
                        method: 'PUT',
                        data: {
                            note: val
                        },
                        url: '/tasks/'+ this.isEditing +'/notes'
                    }).success(function(){
                        this.reload();
                    }.bind(this))
                }
            }
        },
        showNote: function(id) {
            var activeNote = _.findWhere(this.data, {
                id: id
            });
            $('#new-note').hide();
            $('.note-titles li').removeClass('active');
            $('[data-noteid="' + id + '"]').addClass('active');
            $(".panel-primary", $('#active-note')[0]).html(this.activeNoteTemplate(activeNote))
            $('#active-note').show();
        },
        setId: function(id) {
            this.id = id;
            this.reload();
        },
        reload: function() {
            $.get('/tasks/' + this.id + '/notes', function(data){
                this.data = data;
                this.render();
            }.bind(this));
        },
        render: function() {
            $('.note-titles').html('');
            this.data.forEach(function(note){
                $('.note-titles').append(this.noteLiTemplate(note));
            }.bind(this));
            if(this.data.length > 0) {
                this.showNote(this.data[0].id);
            } else {
                $('#add-new-note').trigger('click');
            }
        },
        initialize: function() {
            $('#new-note').hide();
            autosize($('#note-content'));
            $('#active-note').hide();
        }
    }))({
        el: '#notes'
    })

// }).call(this);