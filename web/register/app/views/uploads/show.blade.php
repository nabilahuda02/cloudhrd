<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Size</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @if(isset($uploads) && count($uploads) > 0)
        @foreach($uploads as $upload)
            <tr>
                <td>{{$upload->name}}</td>
                <td>{{$upload->type}}</td>
                <td>{{$upload->size}}</td>
                <td><a href="{{$upload->url}}/{{$upload->name}}" class="btn btn-primary" download>Download</a></td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4">No uploads found</td>
        </tr>
    @endif
    </tbody>
</table>
