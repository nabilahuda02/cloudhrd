<dl>
  <dt>Reference</dt>
  <dd>{{ $item->ref }}</dd>

  <dt>User</dt>
  <dd>{{ User::fullName($item->user_id) }}</dd>

  <dt>Type</dt>
  <dd>{{ $item->type->name }}</dd>

  <dt>Dates</dt>
  <dd>
    <ul>
      @foreach ($item->dates as $date)
        <li>{{ $date->date }}</li>
      @endforeach
    </ul>
  </dd>

  <dt>Remarks</dt>
  <dd>{{ $item->remarks }}</dd>
</dl>