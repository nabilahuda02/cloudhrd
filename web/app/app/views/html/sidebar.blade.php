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
		<h4 class="text-center" style="text-transform:uppercase;">{{ $user->profile->first_name }}</h4>
		<hr style="margin-top:12px;">
	</div>
	<ul class="nav nav-pills nav-stacked">
		<li class="bg-blue border-top-none {{ ($controller === 'My Wall') ? 'active' : '' }}">
			<a href="{{ action('WallController@getIndex') }}">
				Wall
			</a>
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
		<!-- <li class="{{ ($controller === 'Room Booking') ? 'active' : '' }}">
			<a href="{{ action('booking.index') }}">
				Room Booking
			</a>
		</li> -->
		@if($user->is_admin)
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
			<li class="{{ ($controller === 'Subscription') ? 'active' : '' }}">
				<a href="{{ action('SubscriptionController@getIndex') }}">
					Subscription</a>
			</li>
		@endif
		<li class="border-bottom-none">
			<a href="{{ action('AuthController@getLogout') }}">
				Logout
			</a>
		</li>
	</ul>
</div>