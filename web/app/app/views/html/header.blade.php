<nav class="navbar" role="navigation">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="navbar-header">
                    <!-- <a class="navbar-brand" href="{{url('/wall')}}" id="logo"><img src="/assets/images/logo-sands.png" alt=""></a> -->
                    <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="col-sm-6 hidden-xs navbar-extra-padding-top navbar-company-title">
                    <h4>{{$app->master_user->name}}</h4>
                </div>
                <div class="collapse navbar-collapse navbar-extra-padding-top" id="navcol-1">
                    <ul class="nav navbar-nav navbar-right nav-style">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$user->getFullName()}}
                                @if($user->is_admin)<span class="caret"></span>@endif
                            </a>

                            @if($user->is_admin)
                                <ul class="dropdown-menu">
                                    <li class="{{ ($controller === 'User Admin') ? 'active' : '' }}">
                                        <a href="{{ action('AdminUserController@index') }}">
                                            Manage User</a>
                                    </li>
                                    <li class="{{ ($controller === 'Unit Admin') ? 'active' : '' }}">
                                        <a href="{{ action('AdminUnitController@index') }}">
                                            Manage Units</a>
                                    </li>
                                    <li class="{{ ($controller === 'Module Admin') ? 'active' : '' }}">
                                        <a href="{{ action('AdminModuleController@index') }}">
                                            Manage Modules</a>
                                    </li>
                                    <li class="{{ ($controller === 'Audit') ? 'active' : '' }}">
                                        <a href="{{ action('AdminAuditController@getIndex') }}">
                                            Security Audits</a>
                                    </li>
                                    <li class="{{ ($controller === 'Organization') ? 'active' : '' }}">
                                        <a href="{{ action('AdminOrganizationController@index') }}">
                                            Organization</a>
                                    </li>
                                    <!-- <li class="{{ ($controller === 'Subscription') ? 'active' : '' }}">
                                        <a href="{{ action('SubscriptionController@getIndex') }}">
                                            Subscription</a>
                                    </li> -->
                            </ul>
                            @endif
                        </li>
                        <li class="dropdown visible-xs-block"><a class="{{ ($controller == 'Public Wall') ? 'active' : '' }}" href="{{url('/wall')}}">Wall</a></li>
                        <li class="dropdown visible-xs-block"><a class="{{ ($controller == 'Tasks') ? 'active' : '' }}" href="{{action('TasksController@index')}}">Task</a></li>
                        <li class="dropdown visible-xs-block"><a class="{{ ($controller == 'Leaves') ? 'active' : '' }}" href="{{action('LeaveController@index')}}">Leaves</a></li>
                        <li class="dropdown visible-xs-block"><a class="{{ ($controller == 'Medical Claims') ? 'active' : '' }}" href="{{action('MedicalController@index')}}">Medical Claims</a></li>
                        <li class="dropdown visible-xs-block"><a class="{{ ($controller == 'General Claims') ? 'active' : '' }}" href="{{action('GeneralClaimsController@index')}}">General Claims</a></li>
                        <li class="dropdown visible-xs-block"><a class="{{ ($controller == 'Change Requests') ? 'active' : '' }}" href="{{action('ChangeRequestsController@index')}}">Change Request</a></li>
                        <li class="dropdown visible-xs-block"><a class="{{ ($controller == 'Payrolls') ? 'active' : '' }}" href="{{action('PayrollsController@index')}}">Payroll</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="container-fluid big-header big-header-background">
    <div class="row">
        <div class="col-md-12 col-xs-12 big-header-profile">
            <img src="{{ $user->profile->user_image }}" id="profile_image"/>
        </div>
    </div>
    <div class="row">
        <a href="{{action('WallController@getProfile')}}" class="col-md-12 col-xs-12 big-header-profile-list">
            <ul>
                <li>{{$user->email}}</li>
                <li><h5>{{$user->getFullName()}}</h5></li>
                <li><small>{{$user->profile->position}}</small></li>
                <li class="visible-xs-block"><small>{{$app->master_user->name}}</small></li>
            </ul>
        </a>
    </div>
    <div class="row">
        <div class="col-md-8 hidden-xs col-md-offset-2 big-header-menu">
            <ul class="nav nav-pills nav-justified">
                <li><a class="{{ ($controller == 'Public Wall') ? 'active' : '' }}" href="{{url('/wall')}}">Wall</a></li>
                <li><a class="{{ ($controller == 'Tasks') ? 'active' : '' }}" href="{{action('TasksController@index')}}">Task</a></li>
                <li><a class="{{ ($controller == 'Leaves') ? 'active' : '' }}" href="{{action('LeaveController@index')}}">Leaves</a></li>
                <li><a class="{{ ($controller == 'Medical Claims') ? 'active' : '' }}" href="{{action('MedicalController@index')}}">Medical Claims</a></li>
                <li><a class="{{ ($controller == 'General Claims') ? 'active' : '' }}" href="{{action('GeneralClaimsController@index')}}">General Claims</a></li>
                <li><a class="{{ ($controller == 'Change Requests') ? 'active' : '' }}" href="{{action('ChangeRequestsController@index')}}">Change Request</a></li>
                <li><a class="{{ ($controller == 'Payrolls') ? 'active' : '' }}" href="{{action('PayrollsController@index')}}">Payroll</a></li>
            </ul>
        </div>
    </div>
</div>
