{{Former::text('name')
    ->label('Organization Name')
    ->required()
    ->help('This is usually a company name.') }}
{{Former::text('username')
    ->label('Admin Username')
    ->required()}}
{{Former::email('email')
    ->label('Admin Email')
    ->help('Will be used for admin login and invoicing.')
    ->required()}}
{{Former::select('organizationunit_id')
    ->label('Organization Unit')
    ->options(OrganizationUnit::all()->lists('name', 'id'))
    ->required()}}
{{Former::multiselect('roles')
    ->label('Roles')
    ->options(Role::all()->lists('name', 'id'), (isset($user) ? $user->roles->lists('id') : [])) }}
{{ Former::select('reseller_id')
    ->label('Reseller')
    ->options(User::whereHas('roles', function($query){
        $query->where('roles.id', 6);
    })->lists('name', 'id'))
    ->placeholder('Choose One') }}
