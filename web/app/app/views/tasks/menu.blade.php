<div class="task-controls">
    <div class="btn-group pull-right" id="groups">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <span id="active-category">Groups</span> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <div id="group-menu"></div>
            <li class="divider"></li>
            @if($user->is_admin)
            <li id="new-group"><a>Create New Grouping</a></li>
            <li id="new-heading"><a>Create New Tag</a></li>
            <li id="del-group"><a>Delete <span class="group-name"></span> Group</a></li>
            <li class="divider"></li>
            @endif
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
</div>