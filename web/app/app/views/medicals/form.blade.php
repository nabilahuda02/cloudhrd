{{ Former::select('medical_claim_type_id')
    -> label('Type')
    -> options($types->lists('name', 'id') ,null)
    -> class('form-control col-md-4')
    ->required() }}

{{ Former::date('treatment_date')
    -> value(date('Y-m-d'))
    -> max(date('Y-m-d'))
    -> required() }}

{{ Former::number('total')
    -> label('Amount (RM)')
    -> placeholder('0.00')
    -> required() }}