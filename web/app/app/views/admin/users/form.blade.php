{{ Former::email('email')
    ->required() }}

{{ Former::text('first_name')
    ->label('First Name')
    ->required()
    ->value(@$currentuser->profile->first_name) }}

{{ Former::text('last_name')
    ->label('Last Name')
    ->value(@$currentuser->profile->last_name) }}

{{ Former::textarea('address')
    ->label('Address')
    ->value(@$currentuser->profile->address) }}

{{ Former::select('unit_id')
    ->label('Unit')
    ->options(UserUnit::all()->lists('name', 'id'))
    ->required() }}

<?php $i = 0; ?>
@foreach (app()->user_locale->profile_custom_fields as $key => $value)
  @if($value)
    {{ Former::text('user_field_0' . $i)
          ->label($value)
          ->value(@$currentuser->profile->{'user_field_0' . $i}) }}
  @endif
  <?php $i++; ?>
@endforeach

{{Former::radios('is_admin')
  ->radios([
      'True' => array('value' => '1'),
      'False' => array('value' => '0'),
    ])
  ->required()
  ->stacked() }}