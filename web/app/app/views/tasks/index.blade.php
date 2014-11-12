@extends('layouts.module')
@section('script-head')
<script>
var taskCategories = {{$task_categories}};
var taskTags = {{$task_tags}};
</script>
@stop
@section('content')
<div class="col-md-10 col-sm-8" id="tasks">
    <div class="task-controls">
        <div class="btn-group pull-right" id="groups">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <span id="active-category">Groups</span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <div id="group-menu"></div>
                <li class="divider"></li>
                <li id="new-group"><a>Create New Grouping</a></li>
                <li id="new-heading"><a>Create New Tag</a></li>
                <li id="del-group"><a>Delete <span class="group-name"></span> Group</a></li>
                <li class="divider"></li>
                <!-- <li>
                    <a>
                        <label for="control-mine">
                            <input id="control-mine" type="checkbox">
                            Only mine
                        </label>
                    </a>
                </li> -->
                <li>
                    <a>
                        <label for="control-archived">
                            <input id="control-archived" type="checkbox">
                            Include archived
                        </label>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row task-headings" id="task-headings">
    </div>
    <script type="text/template" id="task-headings-template">
    <div class="col-sm-7 panels" id="left"></div>
    <div class="col-sm-5 panels" id="right"></div>
    </script>
    <script type="text/template" id="task-heading-template"><div data-tagid="<%- this.model.id %>" class="panel panel-<%- this.model.get('label') %>">
        <div class="panel-heading">
            <h4 class="panel-title" data-type="taskheading" data-value='{"name":"<%- this.model.get('name') %>","label":"<%- this.model.get('label') %>"}' data-pk="<%- this.model.get('id') %>" data-name="taskheading" data-url="/task-tags/<%- this.model.get('id') %>/update-name"><%- this.model.get('name') %></h4>
        </div>
        <div class="panel-body"><ul class="list-unstyled inner-task"></ul></div>
        <div class="panel-footer input-group">
            <textarea class="form-control new-input" placeholder="Shift + enter to create a new task"></textarea>
            <span class="input-group-btn">
            <button class="btn btn-primary create-todo" type="button"><span class="fa fa-plus"></span></button>
            </span>
        </div>
    </div></script>
    <script type="text/template" id="task-template"><img data-toggle="tooltip" data-taskid="<%- model.id %>" title="Click for more info" data-placement="top" src="<%- (model.owner.profile.user_image) ? model.owner.profile.user_image.replace('original', 'avatar') : '/images/user.jpg' %>" class="has-tooltip feed-avatar hidden-sm hidden-xs">
    <span class="badge badge-primary badge-xs undone-subtasks"><% if(getNotDone(model.subtasks).length > 0) { %><%-getNotDone(model.subtasks).length%><% } %></span>
    <span class="<% if(model.archived) { %>archived<% } %>"><%- model.description %></span>
    <% model.tags.forEach(function(tag){ if(tag.tag_category_id !== currentCategoryId && tag.category) { %> <span data-categoryid="<%-tag.tag_category_id%>" class="category-label label label-<%-tag.label%>">
    <span title="<%- tag.category.name %>: <%- tag.name %>" data-toggle="tooltip" data-placement="top" class="has-tooltip"><%- tag.name.charAt(0) %></span></span><% }}); %>
    <% if(model.archived) { %><span class="unarchive label label-warning"><a title="Click to unarchive" data-toggle="tooltip" data-placement="top" class="has-tooltip">A</a></span> <span class="delete label label-danger"><a title="Click to delete" data-toggle="tooltip" data-placement="top" class="has-tooltip fa fa-trash"></a></span><% } else { %>
    <span class="archive label label-success"><a title="Click to archive" data-toggle="tooltip" data-placement="top" class="has-tooltip">A</a></span>
    <% } %>
    <div class="clearfix"></div></script>
    
    <div class="modal fade" id="task-info">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" data-mode="inline"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-inline followers"></ul>
                            <ul class="list-inline">
                                <li>
                                    <a id="add-follower" class="user-avatar new-user" data-type="select" data-source="/ajax/users" data-title="Search a user"><span class="glyphicon glyphicon-plus"</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <ul class="nav nav-justified nav-pills">
                        <li class="active">
                            <a data-toggle="tab" href="#subtasks">Subtasks</a>
                        </li>
                        <li class="">
                            <a href="#notes" data-toggle="tab">Notes</a>
                        </li>
                        <!-- <li>
                            <a href="#uploads" data-toggle="tab">Uploads</a>
                        </li> -->
                    </ul>
                    <br>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="subtasks">
                            <ul class="list-group">
                                <li class="list-group-item no-padding">
                                    <input class="input-block" id="new-subtask" type="text" placeholder="Shift + Enter to create a new subtask">
                                </li>
                            </ul>
                            <ul class="list-group" id="subtasks-list"></ul>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="notes">
                            <div class="row">
                                <div class="col-md-3">
                                    <a class="btn btn-block btn-primary" id="add-new-note">Create New Note</a>
                                    <br>
                                    <ul class="nav nav-pills nav-stacked note-titles"></ul>
                                </div>
                                <div class="col-md-9">
                                    <div id="active-note">
                                        <div class="panel panel-primary"></div>
                                    </div>
                                    <div class="panel panel-primary" id="new-note">
                                        <div class="panel-heading">
                                            <h3 class="panel-title" id="note-input-title">New Note</h3>
                                        </div>
                                        <div class="panel-body no-padding">
                                            <textarea class="form-control" id="note-content" placeholder="Shift + enter to submit"></textarea>
                                        </div>
                                        <div class="panel-footer text-right">
                                            <a class="btn btn-primary btn-sm" id="save-new-note">Save</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="uploads">
                            <div class="row">
                                <div class="col-md-3">
                                    <a class="btn btn-block btn-primary">Create New Upload</a>
                                    <br>
                                    <ul class="nav nav-pills nav-stacked">
                                        <li class="active">
                                            <a href="#">File 1</a>
                                        </li>
                                        <li draggable="true">
                                            <a href="#">File 2</a>
                                        </li>
                                        <li>
                                            <a href="#">File 3</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-9">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Some Title Huhu</h3>
                                        </div>
                                        <div class="panel-body">
                                            <p>asdasdasd</p>
                                            <ul class="list-unstyled" contenteditable="true">
                                                <li>File Name: somedownload.xls</li>
                                                <li>Mime Type: Microsoft Excel</li>
                                                <li>File Size: 2048 KB</li>
                                            </ul>
                                        </div>
                                        <div class="panel-footer text-right">
                                            <a class="btn btn-info btn-sm">Download</a>
                                            <a class="btn btn-danger btn-sm">Delete</a>
                                            <a class="btn btn-primary btn-sm">Edit</a>
                                        </div>
                                    </div>
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title" draggable="true">
                                            <input type="text" class="form-control" value="Some Title Huhu">
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <textarea class="form-control"></textarea>
                                            <br>
                                            <div class="well well-sm">
                                                <p>Drop a file to replace the existing somedownload.xls</p>
                                            </div>
                                        </div>
                                        <div class="panel-footer text-right">
                                            <a class="btn btn-primary btn-sm">Save</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <a class="btn btn-primary unarchive-task">Unarchive</a>
                                <a class="btn btn-default archive-task">Archive</a>
                                <a class="btn btn-danger delete-task" data-dismiss="delete">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('tasks.template')
    @stop