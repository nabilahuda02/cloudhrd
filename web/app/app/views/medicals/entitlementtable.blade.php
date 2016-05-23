@foreach (MedicalClaim__Type::where('display_wall',1)->get() as $medicalClaimType)
<div class="row donut-charts">
    <div class="col-sm-12 chart-legend">
        <div class="legend-inner text-center" style="display:none">
            <h4>
                @if(isset($medical))
                User's
                @endif
                {{$medicalClaimType->name}}
            </h4>
        </div>
    </div>
    <div class="col-sm-12" style="display:none">
        <div class="widget margin-none">
            @if(isset($medical))
            <div data-path="medical/{{$medicalClaimType->id}}/{{$medical->user_id}}" class="widget-body" id="medical_{{$medicalClaimType->id}}">
                @else
                <div data-path="medical/{{$medicalClaimType->id}}" class="widget-body" id="leave_{{$medicalClaimType->id}}">
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row legend-inner-wrapper">
                <div class="col-sm-4 text-center entitlement">
                    <h6>Entitlement</h6>
                    <div></div>
                </div>
                <div class="col-sm-4 text-center utilized">
                    <h6 style="color:white">Utilized</h6>
                    <div></div>
                </div>
                <div class="col-sm-4 text-center balance">
                    <h6 style="color:white">Balance</h6>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
{{Asset::push('js', 'entitlementchart')}}
