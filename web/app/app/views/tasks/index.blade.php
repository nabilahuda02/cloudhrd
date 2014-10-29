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
                <li class="divider"></li>
                <li>
                    <a>
                        <label for="control-mine">
                            <input id="control-mine" type="checkbox">
                            Only mine
                        </label>
                    </a>
                </li>
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
            <h4 class="panel-title" data-type="text" data-pk="<%- this.model.get('id') %>" data-name="name" data-url="/task-tags/<%- this.model.get('id') %>/update-name"><%- this.model.get('name') %></h4>
        </div>
        <div class="panel-body"><ul class="list-unstyled inner-task"></ul></div>
        <div class="panel-footer input-group">
            <textarea class="form-control new-input" placeholder="Shift + enter to create a new task"></textarea>
            <span class="input-group-btn">
            <button class="btn btn-primary create-todo" type="button"><span class="fa fa-plus"></span></button>
            </span>
        </div>
    </div></script>
    <script type="text/template" id="task"><li><img src="/profile/e46eedce7623f5eb78927caa55611e8d/original.png" class="feed-avatar hidden-sm hidden-xs">item 1 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates qui blanditiis fugit, numquam nobis amet a aperiam repudiandae officia maxime, quo delectus cum excepturi impedit nesciunt eum, provident adipisci cupiditate?
            <div class="clearfix"></div></li></script>
</div>
<!-- <div class="col-md-10 col-sm-8" id="tasks">
    <div class="row task-headings">
        <div class="col-sm-6"><div class="panel panel-danger">
            <div class="panel-heading">
                <h4 class="panel-title">Title</h4>
            </div>
            <div class="panel-body"><ul class="list-unstyled inner-task"></ul></div>
            <div class="panel-footer">
                <textarea class="form-control new-input" rows="1" placeholder="Create New"></textarea>
                <div class="pull-right">
                    <button class="btn btn-primary btn-sm" type="button"><span class="fa fa-plus"></span></button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div><div class="panel panel-warning">
        <div class="panel-heading">
            <h4 class="panel-title">Title</h4>
        </div>
        <div class="panel-body"><ul class="list-unstyled inner-task">
            <li><img src="/profile/e46eedce7623f5eb78927caa55611e8d/original.png" class="feed-avatar hidden-sm hidden-xs">
                item 2
                <div class="clearfix"></div></li>
                <li><img src="/profile/e46eedce7623f5eb78927caa55611e8d/original.png" class="feed-avatar hidden-sm hidden-xs">
                    item 3
                    <div class="clearfix"></div></li></ul>
                </div>
            </div><div class="panel panel-warning">
            <div class="panel-heading">
                <h4 class="panel-title">Title</h4>
            </div>
        <div class="panel-body"><ul class="list-unstyled inner-task"></ul>
    </div>
</div></div>
<div class="col-sm-6"><div class="panel panel-success">
    <div class="panel-heading">
        <h4 class="panel-title">Title</h4>
    </div>
<div class="panel-body"><ul class="list-unstyled inner-task"></ul>
</div>
</div></div>
<div class="col-sm-6"><div class="panel panel-warning">
<div class="panel-heading">
<h4 class="panel-title">Title</h4>
</div>
<div class="panel-body"><ul class="list-unstyled inner-task"></ul>
</div>
</div></div>
</div>
</div> -->
@include('tasks.template')
@stop