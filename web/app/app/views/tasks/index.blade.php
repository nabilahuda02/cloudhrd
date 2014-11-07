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
    <script type="text/template" id="task-template"><img src="<%- model.owner.profile.user_image %>" class="feed-avatar hidden-sm hidden-xs">
        <%- model.description %> 
        <% model.tags.forEach(function(tag){ if(tag.tag_category_id !== currentCategoryId && tag.category) { %> <span class="label label-<%-tag.label%>">
                <span title="<%- tag.category.name %>: <%- tag.name %>" data-toggle="tooltip" data-placement="top" class="has-tooltip"><%- tag.name.charAt(0) %></span></span><% }}); %>
        <% if(model.archived) { %><span class="unarchive label label-warning"><a title="Click to unarchive" data-toggle="tooltip" data-placement="top" class="has-tooltip">A</a></span><% } else { %>
            <span class="archive label label-success"><a title="Click to archive" data-toggle="tooltip" data-placement="top" class="has-tooltip">A</a></span>
        <% } %>
        <div class="clearfix"></div></script>
</div>
@include('tasks.template')
@stop