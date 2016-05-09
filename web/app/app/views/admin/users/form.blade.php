<div class="row">
    <div class="col-md-3">
        {{ Former::email('email')
            ->required() }}
    </div>
    <div class="col-md-3">
        {{ Former::text('first_name')
            ->label('First Name')
            ->required()
            ->value(@$currentuser->profile->first_name) }}
    </div>
    <div class="col-md-3">
        {{ Former::text('last_name')
            ->label('Last Name')
            ->value(@$currentuser->profile->last_name) }}
    </div>
    <div class="col-md-3">
        {{ Former::text('ic_no')
        ->value(@$currentuser->profile->ic_no)
        ->label('IC No') }}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        {{ Former::textarea('address')
            ->label('Address')
            ->value(@$currentuser->profile->address) }}
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        {{ Former::text('staff_no')
        ->value(isset($currentuser) ? $currentuser->profile->staff_no : '')
        ->label('Staff No')
        ->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::date('date_join')
        ->value(isset($currentuser) ? $currentuser->profile->date_join : '')
        ->label('Joined Date')
        ->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::date('resigned_date')
        ->value(isset($currentuser) ? $currentuser->profile->resigned_date : '')
        ->label('Resigned Date') }}
    </div>
    <div class="col-md-4">
        {{ Former::select('unit_id')
        ->label('Unit')
        ->options(UserUnit::all()->lists('name', 'id'))
        ->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::select('employee_type')
        ->value(@$currentuser->profile->employee_type)
        ->options(UserProfile::$employeeTypeOptions)
        ->label('Employee Type') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('position')
        ->value(isset($currentuser) ? $currentuser->profile->position : '')
        ->label('Position')
        ->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::text('salary')
        ->value(isset($currentuser) ? $currentuser->profile->salary : '0.00')
        ->label('Salary')
        ->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::text('bank_name')
        ->value(@$currentuser->profile->bank_name)
        ->label('Bank Name') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('bank_account')
        ->value(@$currentuser->profile->bank_account)
        ->label('Bank Account Number') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('kwsp_account')
        ->value(@$currentuser->profile->kwsp_account)
        ->label('EPF Account Number') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('kwsp_contribution')
        ->value(@$currentuser->profile->kwsp_contribution)
        ->label('EPF Employee Contribution Percentage') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('kwsp_employer_contribution')
        ->value(@$currentuser->profile->kwsp_employer_contribution)
        ->label('EPF Employer Contribution Percentage') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('socso_contribution')
        ->value(@$currentuser->profile->socso_contribution)
        ->label('SOCSO Employee Contribution') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('socso_employer_contribution')
        ->value(@$currentuser->profile->socso_employer_contribution)
        ->label('SOCSO Employer Contribution') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('socso_account')
        ->value(@$currentuser->profile->socso_account)
        ->label('SOCSO Account Number') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('lhdn_account')
        ->value(@$currentuser->profile->lhdn_account)
        ->label('PCB Account Number') }}
    </div>
    <div class="col-md-4">
        {{ Former::text('pcb_contribution')
        ->value(@$currentuser->profile->pcb_contribution)
        ->label('PCB Contrubution') }}
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        {{Former::radios('gender')
            ->checked([@$currentuser->profile->gender => true])
            ->radios([
                'Male' => array('value' => '1'),
                'Female' => array('value' => '0'),
            ])
            ->required()
            ->stacked() }}
    </div>
    <div class="col-md-4">
        {{Former::radios('is_admin')
            ->radios([
                'True' => array('value' => '1'),
                'False' => array('value' => '0'),
            ])
            ->required()
            ->stacked() }}
    </div>
</div>
<!--
'bank_name',
'bank_account',
'kwsp_account',
'kwsp_contribution',
'lhdn_account',
'pcb_contribution',
'socso_account',
'socso_contribution',
'salary',
-->
<?php $i = 0;?>
@foreach (app()->user_locale->profile_custom_fields as $key => $value)
@if($value)
{{ Former::text('user_field_0' . $i)
->label($value)
->value(@$currentuser->profile->{'user_field_0' . $i}) }}
@endif
<?php $i++;?>
@endforeach