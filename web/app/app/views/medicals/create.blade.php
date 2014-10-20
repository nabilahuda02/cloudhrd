@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
	@include('html.notifications')
	<div class="col-md-12">
		<div class="page-header">
			@include('medicals.menu')
			<h3>Create Medical Claim</h3>
		</div>
		@include('medicals.entitlementtable')
		{{ Former::horizontal_open(action('MedicalController@store'))
			-> id('MyForm')
			-> rules(['name' => 'required'])
			-> method('POST') }}
		{{Former::hidden('noonce', Helper::noonce())}}
		@if(Auth::user()->administers(MedicalClaim__Main::$moduleId))
		{{ Former::select('user_id')
			-> label('For User')
			-> options(Helper::userArray(), null)
			-> class('form-control col-md-4')
			->required() }}
		@endif
		@include('medicals.form')
		<div class="form-group">
			<label for="upload" class="control-label col-lg-2 col-sm-4">Upload</label>
			<div class="col-lg-10 col-sm-8">
				<div class="dropzone" id="upload" data-path="medicalclaim/temp/{{ Helper::noonce() }}"></div>
			</div>
		</div>
		{{ Former::textarea('remarks') }}
		<div class="form-group">
			<div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
				<input class="btn-large btn-primary btn pull-right" type="submit" value="Submit">
			</div>
		</div>
		{{ Former::close() }}
	</div>
</div>
{{Asset::push('js','app/upload')}}
@stop