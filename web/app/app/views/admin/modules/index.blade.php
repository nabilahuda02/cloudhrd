@extends('layouts.module')
@section('content')
<section id="units">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>
                    Module List
                    {{-- <button class="btn btn-link help-btn" onclick="HelpFile.show('MANAGE_USER.md', 'Manage Users')">
                        <i class="fa fa-question-circle"></i>
                    </button> --}}
                </h2>
            </div>
            <div class="col-sm-6 section-drop-menu" >
                {{-- @include('admin.units.menu') --}}
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('html.notifications')
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Module Name</th>
                            <th class="text-center">Module Verifier</th>
                            <th class="text-center">Module Approver</th>
                            <th class="text-center">Module Owners</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($modules) > 0)
                        @foreach($modules as $i => $module)
                        <tr>
                            <td class="text-center">{{ $module->name }}</td>
                            <td class="text-center">{{ $module->verifierName() }}</td>
                            <td class="text-center">{{ $module->approverName() }}</td>
                            <td class="text-center">
                                <ul>
                                    @foreach($module->users as $usr)
                                    <li>{{ User::fullName($usr->id) }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-xs ">
                                    <a href="{{action('AdminModuleController@edit',array($module->id))}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5">No modules found.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@stop
