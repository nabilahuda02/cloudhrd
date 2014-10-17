<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>Ref</th>
      <th>Treatment Date</th>
      <th>User</th>
      <th>Status</th>
      <th>Type</th>
      <th>Total</th>
      @if(isset($showaction))
        <th>Action</th>
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach ($datas as $data)
      <tr>
        <td>{{ $data->ref }}</td>
        <td>{{ $data->treatment_date }}</td>
        <td>{{ $data->user->profile->userName() }}</td>
        <td>{{ $data->status->name }}</td>
        <td>{{ $data->type->name }}</td>
        <td>{{ $data->total }}</td>
        @if(isset($showaction))
          <td><a href="{{ action('medical.show', $data->id) }}" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
        @endif
      </tr>
    @endforeach
  </tbody>
</table>