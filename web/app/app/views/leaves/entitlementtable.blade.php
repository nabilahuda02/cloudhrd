<div class="row">
    @foreach (Leave__Type::where('display_wall',1)->get() as $leaveType)
    <div class="col-sm-10 col-sm-offset-1">
        <div class="row donut-charts">
            <div class="col-sm-12 chart-legend">
                <div class="legend-inner  text-center" style="display:none">
                    <h4>
                        @if(isset($leave))
                        User's
                        @endif
                        {{$leaveType->name}}
                    </h4>
                </div>
            </div>
            <div class="col-sm-12" style="display:none">
                <div class="widget margin-none">
                    @if(isset($leave))
                    <div data-path="leave/{{$leaveType->id}}/{{$leave->user_id}}" class="widget-body" id="leave_{{$leaveType->id}}">
                    @else
                    <div data-path="leave/{{$leaveType->id}}" class="widget-body" id="leave_{{$leaveType->id}}">
                    @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row legend-inner-wrapper">
                    <div class="col-md-4 text-center entitlement">
                        <h6>Entitlement</h6>
                        <div></div>
                    </div>
                    <div class="col-md-4 text-center utilized">
                        <h6 style="color:white">Utilized</h6>
                        <div></div>
                    </div>
                    <div class="col-md-4 text-center balance">
                        <h6 style="color:white">Balance</h6>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    {{Asset::push('js', 'entitlementchart')}}
</div>
