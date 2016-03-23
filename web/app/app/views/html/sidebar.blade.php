<div class="col-md-2 col-sm-4">
    <div class="profile">
        <div class="profile-avatar">
            <div class="text-center">
                @if($user->profile->user_image)
                    <img src="{{ $user->profile->user_image }}" id="profile_image"/>
                @else
                    <img src="/images/user.png" id="profile_image"/>
                @endif
            </div>
        </div>
        <h4 class="text-center hover-show-hidden" style="text-transform:uppercase;">{{ $user->profile->first_name }} <a class="is-hidden" href="/wall/profile"><span class="fa fa-cog"></span></a></h4>
        <hr style="margin-top:12px;">
    </div>
    <ul class="nav nav-pills nav-stacked hidden-xs">
        <li class="bg-blue border-top-none {{ ($controller === 'Public Wall') ? 'active' : '' }}">
            <a href="{{ action('WallController@getIndex') }}">
                Wall
            </a>
        </li>
        <li class="{{ ($controller === 'Tasks') ? 'active' : '' }}">
            <a href="{{ action('TasksController@index') }}">
                Tasks</a>
        </li>
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
        <li class="{{ ($controller === 'Payrolls') ? 'active' : '' }}">
            <a href="{{ action('PayrollsController@index') }}">
                Payrolls
            </a>
        </li>
        @if($user->is_admin)
        <li class="">
            <a href="#admin_menu_sidebar" class="" data-toggle="collapse">Administrator <span class="fa fa-raquo"></span></a>
            <ul class="collapse list-unstyled @if(in_array($controller, ['Unit Admin', 'User Admin', 'Module Admin', 'Audit', 'Organization', 'Subscription'])) in @endif" id="admin_menu_sidebar">
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
