<table class="table table-bordered">
    <tr><td>{{ _lang('User Name') }}</td><td>{{ $withdrawRequest->user->name }}</td></tr>
    <tr><td>{{ _lang('User Email') }}</td><td>{{ $withdrawRequest->user->email }}</td></tr>
    <tr><td>{{ _lang('Withdraw Method') }}</td><td>{{ $withdrawRequest->method->name }}</td></tr>
    <tr><td>{{ _lang('Amount') }}</td><td>{{ decimalPlace($withdrawRequest->amount, currency($withdrawRequest->method->currency->name)) }}</td></tr>
    <tr><td>{{ _lang('Description') }}</td><td>{{ $withdrawRequest->description }}</td></tr>
    @foreach($withdrawRequest->requirements as $key => $value)
    <tr>
        <td>{{ ucwords(str_replace('_',' ',$key)) }}</td>
        <td>{{ $value }}</td>
    </tr>
    @endforeach
    <tr>
        <td>{{ _lang('Attachment') }}</td>
        <td>
            {!! $withdrawRequest->attachment == "" ? '' : '<a href="'. asset('public/uploads/media/'.$withdrawRequest->attachment) .'" target="_blank">'._lang('View Attachment').'</a>' !!}
        </td>
    </tr>
    <tr><td>{{ _lang('Status') }}</td><td>{!! xss_clean(transaction_status($withdrawRequest->status)) !!}</td></tr>
</table>