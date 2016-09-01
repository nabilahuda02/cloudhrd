@extends('layouts.module')
@section('content')
<section id="medical">
    <div class="container">
        <div class="row">
            <div class="col-xs-9">
                <h2>
                    @if($medical->is_paid)
                        <span class="pull-right label label-success">Paid</span>
                    @else
                        <span class="pull-right label label-warning">Unpaid</span>
                    @endif
                    Medical Claims Form: {{$medical->ref}}
                    <button class="btn btn-link help-btn" onclick="HelpFile.show('USER_LEAVE.md', 'Medical')">
                        <i class="fa fa-question-circle"></i>
                    </button>
                </h2>
            </div>
            <div class="col-xs-3 section-drop-menu" >
                @include('medicals.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                @include('html.notifications')
                {{ Former::horizontal_open(action('MedicalController@store'))
                    ->id('medicalForm')
                    ->rules(['name' => 'required'])
                    ->method('POST') }}
                {{ Former::text('created_at')
                    ->label('Created At')
                    ->value(Helper::timestamp($medical->created_at))
                    ->readonly()
                    ->disabled() }}
                {{ Former::text('ref')
                    ->label('Reference')
                    ->value($medical->ref)
                    ->readonly()
                    ->disabled() }}
                {{ Former::text('user_id')
                    ->label('Employee')
                    ->value(User::fullName($medical->user_id))
                    ->readonly()
                    ->disabled() }}
                {{ Former::text('status')
                    ->label('Status')
                    ->value($medical->status->name)
                    ->readonly()
                    ->disabled() }}
                {{Former::populate($medical)}}
                @include('medicals.form')
                {{ Asset::push('js','upload')}}
                @if($medical->uploads()->count() > 0)
                    <div class="form-group">
                        <label for="dates" class="control-label col-lg-2 col-sm-4">Uploaded</label>
                        <div class="col-lg-10 col-sm-8">
                            <ul class="list-inline uploaded">
                                @foreach ($medical->uploads as $file)
                                <li class="view_uploaded" data-url="{{$file->file_url}}">
                                    <a href="{{$file->file_url}}" target="_blank">{{$file->file_name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                {{ Former::textarea('remarks')
                    ->value($medical->remarks) }}
                <div class="form-group">
                    <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                        @include('medicals.actions-buttons')
                    </div>
                </div>
                {{ Former::close() }}
            </div>
            <div class="col-md-3">
                @include('medicals.entitlementtable')
            </div>
        </div>
    </div>
</section>
@stop
@section('script')
@include('medicals.actions-scripts')
<script>
$('input:not([type=hidden],[type=search]),select,textarea').attr({
    readonly: true,
    disabled: true
});
$('#medicalForm').on('submit',function(e){
    e.preventDefault();
    return false;
});
</script>
@stop
