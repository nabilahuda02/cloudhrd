<dl>
  <dt>Reference</dt>
  <dd>{{ $item->ref }}</dd>

  <dt>User</dt>
  <dd>{{ Helper::userName($item->user_id) }}</dd>

  <dt>Booking Date</dt>
  <dd>{{ $item->booking_date }}</dd>

  <dt>Booking Slots</dt>
  <dd>
    <ul>
    @foreach ($item->slots as $slot)
      <li>{{ $slot->pretty() }}</li>
    @endforeach
    </ul>
  </dd>

  <dt>Room</dt>
  <dd>{{ $item->room->name }}</dd>

  <dt>Purpose</dt>
  <dd>{{ $item->purpose }}</dd>

  <dt>Remarks</dt>
  <dd>{{ $item->remarks }}</dd>
</dl>