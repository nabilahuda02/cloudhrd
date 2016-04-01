<div class="row">
    <div class="col-md-4">
        {{ Former::email('email')
            ->required() }}
    </div>
    <div class="col-md-4">
        {{ Former::text('first_name')
            ->label('First Name')
            ->required()
            ->value(@$currentuser->profile->first_name) }}
    </div>
    <div class="col-md-4">
        {{ Former::text('last_name')
            ->label('Last Name')
            ->value(@$currentuser->profile->last_name) }}
    </div>
    <div class="col-md-12">
        {{ Former::textarea('address')
            ->label('Address')
            ->value(@$currentuser->profile->address) }}
    </div>
    <div class="col-md-3">
        {{ Former::select('unit_id')
        ->label('Unit')
        ->options(UserUnit::all()->lists('name', 'id'))
        ->required() }}
    </div>
    <div class="col-md-3">
        {{ Former::text('salary')
        ->value(isset($currentuser) ? $currentuser->profile->salary : '0.00')
        ->label('Salary')
        ->required() }}
    </div>
    <div class="col-md-3">
        {{ Former::text('bank_name')
        ->value(@$currentuser->profile->bank_name)
        ->label('Bank Name') }}
    </div>
    <div class="col-md-3">
        {{ Former::text('bank_account')
        ->value(@$currentuser->profile->bank_account)
        ->label('Bank Account Number') }}
    </div>
    <div class="col-md-3">
        {{ Former::text('kwsp_account')
        ->value(@$currentuser->profile->kwsp_account)
        ->label('KWSP Account Number') }}
    </div>
    <div class="col-md-3">
        {{ Former::text('kwsp_contribution')
        ->value(@$currentuser->profile->kwsp_contribution)
        ->label('KWSP Contribution Percentage') }}
    </div>
    <div class="col-md-3">
        {{ Former::text('socso_account')
        ->value(@$currentuser->profile->socso_account)
        ->label('SOCSO Account Number') }}
    </div>
    <div class="col-md-3">
        {{ Former::text('socso_contribution')
        ->value(@$currentuser->profile->socso_contribution)
        ->label('SOCSO Contribution') }}
    </div>
    <div class="col-md-3">
        {{ Former::text('lhdn_account')
        ->value(@$currentuser->profile->lhdn_account)
        ->label('PCB Account Number') }}
    </div>
    <div class="col-md-3">
        {{ Former::text('pcb_contribution')
        ->value(@$currentuser->profile->pcb_contribution)
        ->label('PCB Contrubution') }}
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
{{Former::radios('is_admin')
->radios([
'True' => array('value' => '1'),
'False' => array('value' => '0'),
])
->required()
->stacked() }}