{{Asset::push('js','app/profile.js')}}

<div>
  <br>
  <h4>
      <a href="/wall/edit-profile" class="btn btn-small btn-primary pull-right"><i class="fa fa-key"></i></a>
     Personal Detail
  </h4>
  <br>
  {{ Former::open() }}
  <div class="col-md-6">
    {{ Former::text('user_profile["first_name"]')
    -> label('First Name')
    -> value($currentuser->profile->first_name)
    -> readonly()
    -> disabled() }}
  </div>
  <div class="col-md-6">
    {{ Former::text('user_profile["last_name"]')
    -> label('Last Name')
    -> value($currentuser->profile->last_name)
    -> readonly()
    -> disabled() }}
  </div>
  <!-- <div class="col-md-12">
    {{ Former::textarea('user_profile["address"]')
    -> label('Address')
    -> value('Lot 1234, Kampung Pisang')
    -> readonly()
    -> disabled() }}
  </div> -->
  {{Former::close()}}
  <div class="myclear"></div>
  <hr>
  <br>
</div>


<div id="profile_contacts">
  <br>
  <h4>
    Contact Detail
  </h4>
  <br>
  <div class="row">
    <div class="col-md-6">
      {{ Former::text('name')
      -> label('Contact Type')
      -> placeholder('Mobile') }}
    </div>
    <div class="col-md-6">
      {{ Former::text('number')
      -> label('Contact Number')
      -> placeholder('+60312345678') }}
    </div>
    <div class="clearfix"></div>
    <br>
  </div>
  <hr>
  <br>
</div>

<div id="profile_educations">
  <br>
  <h4>
  Education Detail
  </h4>
  <br>
  <div class="row">
    <div class="col-md-8">
      {{ Former::text('institution')
      -> label('Institution')
      -> placeholder('ABC University') }}
    </div>
    <div class="col-md-4">
      {{ Former::input('start_date')
      -> type('date')
      -> label('Start Date')
      -> placeholder('01-01-1990') }}
    </div>
    <div class="col-md-8">
      {{ Former::text('course')
      -> label('Course')
      -> placeholder('Diploma') }}
    </div>
    <div class="col-md-4">
      {{ Former::input('end_date')
      -> type('date')
      -> label('End Date')
      -> placeholder('01-01-1992') }}
    </div>
    <div class="clearfix"></div>
    <br>
  </div>
  <hr>
  <br>
</div>

<div id="profile_emergency">
  <br>
  <h4>
  Emergency Contacts
  </h4>
  <br>
  <div class="row">
    <div class="col-md-6">
      {{ Former::text('name')
      -> label('Contact Person')
      -> placeholder('Norseta Saidon') }}
    </div>
    <div class="col-md-6">
      {{ Former::text('phone')
      -> label('Contact Person Number')
      -> placeholder('03 12345678') }}
    </div>
    <div class="col-md-12">
      {{ Former::textarea('address')
      -> id('profile_emaddress')
      -> label('Address') }}
    </div>
    <div class="clearfix"></div>
    <br>
  </div>
  <hr>
  <br>
</div>

<div id="profile_employment">
  <br>
  <h4>
    Employment History
  </h4>
  <br>
  <div class="row">
    <div class="col-md-3">
      {{ Former::text('company_name')
      -> label('Company Name')
      -> placeholder('ABC Company') }}
    </div>
    <div class="col-md-3">
      {{ Former::text('position')
      -> label('Position')
      -> placeholder('Manager') }}
    </div>
    <div class="col-md-3">
      {{ Former::input('start_date')
      -> label('Start Date')
      -> type('date')
      -> placeholder('01-01-1990') }}
    </div>
    <div class="col-md-3">
      {{ Former::input('end_date')
      -> label('End Date')
      -> type('date')
      -> placeholder('01-01-1992') }}
    </div>
    <div class="clearfix"></div>
    <br>
  </div>
  <hr>
  <br>
</div>

<div id="profile_family">
  <br>
  <h4>
    Family Members
  </h4>
  <br>
  <div class="row">
    <div class="col-md-4">
      {{ Former::text('name')
      -> label('Family Member Name')
      -> placeholder('John Doe') }}
    </div>
    <div class="col-md-4">
      {{ Former::input('dob')
      -> type('date')
      -> label('Date of Birth')
      -> placeholder('01-01-1990') }}
    </div>
    <div class="col-md-4">
      {{ Former::select('lookup_family_relationship_id')
      -> label('Family Relation')
      -> options(Lookup__FamilyRelationship::all()->lists('name', 'id')) }}
    </div>
    <div class="clearfix"></div>
    <br>
  </div>
  <hr>
  <br>
</div>