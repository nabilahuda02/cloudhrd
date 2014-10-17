<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>Ref</th>
      <th>User</th>
      <th>Status</th>
      <th>Booking Date</th>
      <th>Purpose</th>
      <th>Room</th>
      @if(isset($showaction))
        <th>Action</th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($datas as $data)
      <tr>
        <td>{{ $data->ref }}</td>
        <td>{{ $data->user->profile->userName() }}</td>
        <td>{{ $data->status->name }}</td>
        <td>{{ $data->booking_date }}</td>
        <td>{{ $data->purpose }}</td>
        <td>{{ $data->room->name }}</td>
        @if(isset($showaction))
          <td><a href="{{ action('booking.show', $data->id) }}" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>