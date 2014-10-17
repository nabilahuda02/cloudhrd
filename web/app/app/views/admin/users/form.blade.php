{{ Former::email('email')
    ->required() }}

{{ Former::text('first_name')
    ->label('First Name')
    ->required()
    ->value(@$currentuser->profile->first_name) }}

{{ Former::text('last_name')
    ->label('Last Name')
    ->value(@$currentuser->profile->last_name) }}

{{ Former::select('unit_id')
    ->label('Unit')
    ->options(UserUnit::all()->lists('name', 'id'))
    ->required() }}


{{Former::radios('is_admin')
  ->radios([
      'True' => array('value' => '1'),
      'False' => array('value' => '0'),
    ])
  ->required()
  ->stacked() }}