@extends('layouts.module')
@section('content')
{{Asset::push('js','profile')}}

<section id="profile">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>
                    Personal Detail
                </h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <img src="{{ $user->profile->user_image }}" id="profile_image_editable" class="img-responsive center"/>
                    </div>
                    <div class="col-md-12">
                        <a href="/wall/change-password" class="btn btn-block btn-primary btn-profile">Change Password</a>
                        <a href="{{action('ProfileController@requestUpdate')}}" class="btn btn-block btn-primary btn-profile">Update Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                {{ Former::open() }}
                <div class="row">
                    <div class="col-md-3">
                        {{ Former::text('user_profile["email"]')
                            -> label('Email')
                            -> value($currentuser->email)
                            -> readonly()
                            -> disabled() }}
                    </div>
                    <div class="col-md-3">
                        {{ Former::text('user_profile["first_name"]')
                            -> label('First Name')
                            -> value($currentuser->profile->first_name)
                            -> readonly()
                            -> disabled() }}
                    </div>
                    <div class="col-md-3">
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
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {{ Former::select('unit_id')
                        ->label('Unit')
                        ->options(UserUnit::all()->lists('name', 'id'))
                        ->readonly()
                        ->disabled() }}
                    </div>

                    <div class="col-md-4">
                        {{ Former::text('position')
                        ->value(@$currentuser->profile->position)
                        ->label('Position')
                        ->readonly()
                        ->disabled() }}
                    </div>

                    <div class="col-md-4">
                        {{ Former::text('bank_name')
                        ->value(@$currentuser->profile->bank_name)
                        ->label('Bank Name')
                        ->readonly()
                        ->disabled() }}
                    </div>
                    <div class="col-md-4">
                        {{ Former::text('bank_account')
                        ->value(@$currentuser->profile->bank_account)
                        ->label('Bank Account Number')
                        ->readonly()
                        ->disabled() }}
                    </div>

                    <div class="col-md-4">
                        {{ Former::text('kwsp_account')
                        ->value(@$currentuser->profile->kwsp_account)
                        ->label('KWSP Account Number')
                        ->readonly()
                        ->disabled() }}
                    </div>

                    <div class="col-md-4">
                        {{ Former::text('socso_account')
                        ->value(@$currentuser->profile->socso_account)
                        ->label('SOCSO Account Number')
                        ->readonly()
                        ->disabled() }}
                    </div>

                    <div class="col-md-4">
                        {{ Former::text('lhdn_account')
                        ->value(@$currentuser->profile->lhdn_account)
                        ->label('PCB Account Number')
                        ->readonly()
                        ->disabled() }}
                    </div>
                </div>
                <div class="row" style="display:none">
                    <?php $i = 0;?>
                    @foreach (app()->user_locale->profile_custom_fields as $key => $value)
                      @if($value)
                        <div class="col-md-12">
                        {{ Former::text('user_field_0' . $i)
                              ->label($value)
                              ->disabled()
                              ->value(@$currentuser->profile->{'user_field_0' . $i}) }}
                        </div>
                      @endif
                      <?php $i++;?>
                    @endforeach
                </div>
                {{Former::close()}}
                <div class="myclear"></div>
                <hr>
                <br>
                <!-- if current user have upload thing -->
                @if($currentuser->uploads->count() > 0)
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
                @endif
                <div style="display:none">
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
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop