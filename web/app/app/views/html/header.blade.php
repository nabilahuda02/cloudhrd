<nav class="navbar" role="navigation">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                       <span class="sr-only">Toggle navigation</span>
                       <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#" id="logo">
                        <img src="/assets/images/logo-sands.png" alt="">
                    </a>
                </div>
                <div class="col-md-6 hidden-xs navbar-extra-padding-top navbar-company-title">
                    <h4>Sands Consulting Sdn Bhd</h4>
                </div>
                <div class="collapse navbar-collapse navbar-extra-padding-top" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right nav-style">
                        <li>
                            <a href="#"><i class="fa fa-search"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bell"></i></a>
                        </li>
                        <li>
                            <a href="#" data-toggle="collapse">{{$user->getFullName()}} <i class="fa fa-caret-down"></i></a>
                        </li>
                    </ul>
                </div>
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
           </ul>
       </a>
    </div>
    <div class="row">
        <div class="col-md-8 hidden-xs col-md-offset-2 big-header-menu">
            <ul class="nav nav-pills nav-justified">
               <li><a href="/index.html">Wall</a></li>
               <li><a href="/task.html">Task</a></li>
               <li><a href="{{action('LeaveController@index')}}">Leaves</a></li>
               <li><a href="/medical-claim.html">Medical Claims</a></li>
               <li><a href="/general-claim.html">General Claims</a></li>
               <li><a href="#">Payroll</a></li>
           </ul>
        </div>
    </div>
</div>
