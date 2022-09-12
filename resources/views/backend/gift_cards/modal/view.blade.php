<table class="table table-bordered">
	<tr><td>{{ _lang('Code') }}</td><td>{{ $giftcard->code }}</td></tr>
	<tr><td>{{ _lang('Currency') }}</td><td>{{ $giftcard->currency->name }}</td></tr>
	<tr><td>{{ _lang('Amount') }}</td><td>{{ decimalPlace($giftcard->amount, currency($giftcard->currency->name)) }}</td></tr>
	<tr><td>{{ _lang('Status') }}</td><td>{!! $giftcard->status == 0 ? xss_clean(show_status(_lang('Unused'),'primary')) : xss_clean(show_status(_lang('Used'),'danger')) !!}</td></tr>
	<tr><td>{{ _lang('Used By') }}</td><td>{{ $giftcard->user->name }}</td></tr>
	<tr><td>{{ _lang('Used At') }}</td><td>{{ $giftcard->used_at }}</td></tr>
</table>

