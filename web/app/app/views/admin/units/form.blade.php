{{ Former::text('name')
    ->value(@$unit->name) }}

{{ Former::select('parent_id')
    ->label('Unit Parent')
    ->placeholder('Choose One')
    ->options(UserUnit::all()->lists('name', 'id')) }}

{{ Former::select('user_id')
    ->label('Unit Head')
    ->options(Helper::userArray())
    ->required() }}