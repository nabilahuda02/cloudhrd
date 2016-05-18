{{ Former::text('name')
    ->value(@$unit->name) }}

{{ Former::select('parent_id')
    ->label('Unit Parent')
    ->options(['' => 'Choose One'] + UserUnit::all()->lists('name', 'id')) }}

{{ Former::select('user_id')
    ->label('Unit Head')
    ->options(Helper::userArray())
    ->required() }}

{{Former::radios('is_onpayroll')
    ->label('Is On Payroll')
    ->checked(['1' => true])
    ->radios([
        'True' => array('value' => '1'),
        'False' => array('value' => '0'),
    ])
    ->required() }}
