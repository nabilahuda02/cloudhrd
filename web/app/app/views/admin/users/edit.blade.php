@extends('layouts.module')
@section('content')
<section id="users">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>
                    Edit {{User::fullName($currentuser->id)}}
                    {{-- <button class="btn btn-link help-btn" onclick="HelpFile.show('MANAGE_USER.md', 'Manage Users')">
                        <i class="fa fa-question-circle"></i>
                    </button> --}}
                </h2>
            </div>
            <div class="col-sm-6 section-drop-menu" >
                @include('admin.users.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('html.notifications')
                {{ Former::vertical_open(action('AdminUserController@update', $currentuser->id))
                    -> id('leaveForm')
                    -> rules(['name' => 'required'])
                    -> method('POST') }}
                {{ Former::hidden('_method', 'PUT') }}
                {{ Former::populate($currentuser) }}
                @include('admin.users.form')
                <div class="form-group">
                    <button type="button" id="delete_user" class="btn-large btn-secondary btn pull-right">Delete</button>
                    <input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
                </div>
                {{ Former::close() }}
                <div class="clearfix"></div>
                <div class="form-group hidden">
                    <h3>Attached Documents</h3>
                    <hr>
                    <ul class="media-list">
                        @foreach ($currentuser->uploads as $upload)
                        <li class="media">
                            <a class="pull-left" download href="{{$upload->file_url}}"><img class="media-object" src="{{$upload->thumb_url}}" width="64px" height="64px"></a>
                            <div class="media-body">
                                <h4 class="media-heading">{{$upload->file_name}}</h4>
                                <p>{{$upload->humanSize()}}</p>
                                <button class="btn btn-sm btn-danger delete-upload" data-id="{{$upload->id}}">Delete</button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="form-group hidden">
                    <label for="dates" class="control-label">Upload Documents</label>
                    <div>
                        <div class="dropzone" id="upload" data-path="users/profile/{{$currentuser->id}}"></div>
                    </div>
                    <hr>
                </div>
                <div class="clearfix"></div><br/><br/>
                {{ Former::open(action('AdminUserController@destroy', $currentuser->id))->id('delete_form') }}
                {{ Former::hidden('_method', 'DELETE') }}
                {{ Former::close() }}
                @include('profiles.admin')
            </div>
            <script>
            var user_profile_id = {{$currentuser->profile->id}};
            </script>
        </div>
    </div>
</section>
@stop
@section('script')
<script>
$('#delete_user').click(function(){
    bootbox.confirm('Are you sure you want to delete {{User::fullName($currentuser->id)}}?', function(res){
        if(res) {
            $('#delete_form').submit();
        }
    });
});

$('.delete-upload').click(function(e){
    var el = $(e.currentTarget);
    var id = el.data('id');
    if(el && id) {
        bootbox.confirm('Are you sure you want to delete this file?', function(res){
            if(res) {
                $.get('/useradminprofile/delete-file/' + id, function(){
                    el.parents('li').remove();
                })
            }
        })
    }
})

</script>
@stop
