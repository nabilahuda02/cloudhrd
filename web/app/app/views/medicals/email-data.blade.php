<dl>
  <dt>Reference</dt>
  <dd>{{ $item->ref }}</dd>

  <dt>User</dt>
  <dd>{{ Helper::userName($item->user_id) }}</dd>

  <dt>Type</dt>
  <dd>{{ $item->type->name }}</dd>

  <dt>Treatment Date</dt>
  <dd>{{ $item->treatment_date }}</dd>

  <dt>Amount</dt>
  <dd>{{ $item->total }}</dd>

  <dt>Remarks</dt>
  <dd>{{ $item->remarks }}</dd>
</dl>