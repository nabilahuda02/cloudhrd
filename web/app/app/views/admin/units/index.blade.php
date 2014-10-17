@extends('layouts.module')
@section('content')
<div class="col-md-10 col-sm-8">
    @include('html.notifications')
    <div class="col-md-12">
        <div class="page-header">
            @include('admin.units.menu')
            <h3>Unit List</h3>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Unit Name</th>
                    <th class="text-center">Unit Parent</th>
                    <th class="text-center">Unit Head</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($units) > 0)
                @foreach($units as $i => $unit)
                <tr>
                    <td class="text-center">{{ $unit->name }}</td>
                    <td class="text-center">{{ $unit->getParentName() }}</td>
                    <td class="text-center">
                        {{ User::fullName($unit->user->id) }}
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-xs ">
                            <a href="{{action('AdminUnitController@edit',array($unit->id))}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="5">No units found.</td>
                </tr>
                @endif
            </tbody>
        </table>
        <hr>
    </div>
</div>
@stop