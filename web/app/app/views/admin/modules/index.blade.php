@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            <h3>Module List</h3>
        </div>
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
        <hr>
    </div>
</div>
@stop
