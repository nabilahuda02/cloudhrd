@extends('layouts.module')
@section('content')
<section id="units">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>
                    Unit List
                    {{-- <button class="btn btn-link help-btn" onclick="HelpFile.show('MANAGE_USER.md', 'Manage Users')">
                        <i class="fa fa-question-circle"></i>
                    </button> --}}
                </h2>
            </div>
            <div class="col-sm-6 section-drop-menu" >
                @include('admin.units.menu')
            </div>
        </div>
    </div>
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('html.notifications')
                <div class="table-responsive">
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
                </div>
            </div>
        </div>
    </div>
</section>
@stop
