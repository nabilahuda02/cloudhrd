<div class="navbar hidden-print main navbar-default" role="navigation">
    <div class="row">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
                <a id="logo" href="{{ action('LeaveController@index') }}" class="navbar-brand">
                    CloudHRD <span class="hidden-xs">| {{ $controller }}</span>
                </a>
            </div>
            <div class="navbar-collapse collapse navbar-right navbar-form search-container">
                <div class="hidden-sm hidden-xs">
                    <div class="form-group">
                        <input type="search" class="form-control input-sm" id="searchinput" placeholder="Search ...">
                    </div>
                    <button class="btn btn-blue" type="button"><i class="fa fa-search"></i></button>
                    <div id="search-results"></div>
                    <div class="clearfix"></div>
                </div>
                <ul class="nav hidden-md hidden-lg hidden-sm main-nav">
                    {{-- <li class="bg-blue border-top-none {{ ($controller === 'Public Wall') ? 'active' : '' }}">
                            <a href="{{ action('WallController@getIndex') }}">
                                Wall
                            </a>
                        </li>
                        <li class="{{ ($controller === 'Tasks') ? 'active' : '' }}">
                            <a href="{{ action('TasksController@index') }}">
                                Tasks</a>
                        </li> --}}
                    <li class="{{ ($controller === 'Leaves') ? 'active' : '' }}">
                        <a href="{{ action('LeaveController@index') }}">
                            Leaves</a>
                    </li>
                    <li class="{{ ($controller === 'Medical Claims') ? 'active' : '' }}">
                        <a href="{{ action('MedicalController@index') }}">
                            Medical Claims
                        </a>
                    </li>
                    <li class="{{ ($controller === 'General Claims') ? 'active' : '' }}">
                        <a href="{{ action('GeneralClaimsController@index') }}">
                            General Claims
                        </a>
                    </li>
                    @if($user->is_admin)
                    <li class="">
                        <a href="#admin_menu" class="" data-toggle="collapse">Administrator <span class="fa fa-raquo"></span></a>
                        <ul class="collapse list-unstyled @if(in_array($controller, ['Unit Admin', 'User Admin', 'Module Admin', 'Audit', 'Organization', 'Subscription'])) in @endif" id="admin_menu">
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
                            <li class="{{ ($controller === 'Subscription') ? 'active' : '' }}">
                                <a href="{{ action('SubscriptionController@getIndex') }}">
                                    Subscription</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <li class="border-bottom-none">
                        <a href="{{ action('AuthController@getLogout') }}">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row row-app margin-none">
