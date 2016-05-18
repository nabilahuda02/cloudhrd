<dl>
    <dt>Reference</dt>
    <dd>{{ $item->ref }}</dd>
    <dt>User</dt>
    <dd>{{ User::fullName($item->user_id) }}</dd>
    <dt>Title</dt>
    <dd>{{ $item->title }}</dd>
    <dt>Entries</dt>
    <dd>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <td>Type</td>
                <td>Description</td>
                <td width="150px">Subtotal</td>
            </tr>
        </thead>
        <tbody id="targettbody">
            @foreach ($item->entries as $entry)
            <tr>
                <td>{{ $entry->type->name }}</td>
                <td>{{ $entry->description }}</td>
                <td class="amount_col">{{ $entry->amount }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </dd>
    <dt>Amount</dt>
    <dd>{{ $item->value }}</dd>
    <dt>Remarks</dt>
    <dd>{{ $item->remarks }}</dd>
</dl>