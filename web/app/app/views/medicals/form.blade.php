{{ Former::select('medical_claim_type_id')
    -> label('Type')
    -> options($types->lists('name', 'id') ,null)
    -> class('form-control col-md-4')
    ->required() }}

{{ Former::text('treatment_date')
    -> data_type('date')
    -> value(Helper::today_short_date())
    -> required() }}

{{ Former::number('total')
    -> label('Amount (' . app()->user_locale->currency_symbol . ')')
    -> step(1 / pow(10, app()->user_locale->decimal_places))
    -> required() }}