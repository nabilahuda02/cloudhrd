@extends('layouts.module')
@section('content')
{{Asset::push('js','app/profile.js')}}
<div class="col-sm-10">
    <div>
        <br>
        <h2>
            <a href="/wall/change-password" class="btn btn-small btn-primary pull-right"><i class="fa fa-key"></i> Change Password</a>
            Personal Detail
        </h2>
        <hr>
        <br>
        {{ Former::open() }}
        <div class="row">
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
            <div class="col-md-12">
                {{ Former::textarea('user_profile["address"]')
                -> label('Address')
                -> value($currentuser->profile->address)
                -> readonly()
                -> disabled() }}
            </div>
            <?php $i = 0; ?>
            @foreach (app()->user_locale->profile_custom_fields as $key => $value)
              @if($value)
                <div class="col-md-12">
                {{ Former::text('user_field_0' . $i)
                      ->label($value)
                      ->disabled()
                      ->value(@$currentuser->profile->{'user_field_0' . $i}) }}
                </div>        
              @endif
              <?php $i++; ?>
            @endforeach
        </div>
        {{Former::close()}}
        <div class="myclear"></div>
        <hr>
        <br>
    </div>
    
    <div class="form-group">
        <h3>Attached Documents</h3>
        <hr>
        <ul class="media-list">
            @foreach ($currentuser->uploads as $upload)
            <li class="media">
                <a class="pull-left" download href="{{$upload->file_url}}"><img class="media-object" src="{{$upload->thumb_url}}" width="64px" height="64px"></a>
                <div class="media-body">
                    <h4 class="media-heading">{{$upload->file_name}}</h4>
                    <p>{{$upload->humanSize()}}</p>
                </div>
            </li>
            @endforeach
        </ul>
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
                -> label('Contact Type') }}
            </div>
            <div class="col-md-6">
                {{ Former::text('number')
                -> label('Contact Number') }}
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
                -> label('Institution') }}
            </div>
            <div class="col-md-4">
                {{ Former::input('start_date')
                -> type('date')
                -> label('Start Date') }}
            </div>
            <div class="col-md-8">
                {{ Former::text('course')
                -> label('Course') }}
            </div>
            <div class="col-md-4">
                {{ Former::input('end_date')
                -> type('date')
                -> label('End Date') }}
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
        Emergency Contact Information
        </h4>
        <br>
        <div class="row">
            <div class="col-md-6">
                {{ Former::text('name')
                -> label('Contact Person') }}
            </div>
            <div class="col-md-6">
                {{ Former::text('phone')
                -> label('Contact Person Number') }}
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
                -> label('Company Name') }}
            </div>
            <div class="col-md-3">
                {{ Former::text('position')
                -> label('Position') }}
            </div>
            <div class="col-md-3">
                {{ Former::input('start_date')
                -> label('Start Date')
                -> type('date') }}
            </div>
            <div class="col-md-3">
                {{ Former::input('end_date')
                -> label('End Date')
                -> type('date') }}
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
</div>
@stop