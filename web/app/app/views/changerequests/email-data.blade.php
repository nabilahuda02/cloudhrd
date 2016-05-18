<dt>Reference</dt>
<dd>{{ $item->ref }}</dd>
<dt>User</dt>
<dd>{{ User::fullName($item->user_id) }}</dd>
<dt>Changes</dt>
<table class="table table-bordered" cellspacing="0" cellpadding="10" border="0" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
        <tr>
            <th style="border: 1px solid black;padding: 5px;">Column</th>
            <th style="border: 1px solid black;padding: 5px;">Old Value</th>
            <th style="border: 1px solid black;padding: 5px;">New Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach($item->items as $entry)
        <tr>
            <td style="border: 1px solid black;padding: 5px;">{{$entry->field_name}}</td>
            <td style="border: 1px solid black;padding: 5px;">{{$entry->old_value}}</td>
            <td style="border: 1px solid black;padding: 5px;">{{$entry->new_value}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
