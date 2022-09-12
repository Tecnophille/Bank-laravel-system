<table class="table table-bordered">
	<tr><td>{{ _lang('Name') }}</td><td>{{ $fdrplan->name }}</td></tr>
	<tr><td>{{ _lang('Minimum Amount') }}</td><td>{{ $fdrplan->minimum_amount }}</td></tr>
	<tr><td>{{ _lang('Maximum Amount') }}</td><td>{{ $fdrplan->maximum_amount }}</td></tr>
	<tr><td>{{ _lang('Interest Rate') }}</td><td>{{ $fdrplan->interest_rate }} %</td></tr>
	<tr><td>{{ _lang('Duration') }}</td><td>{{ $fdrplan->duration.' '.ucwords($fdrplan->duration_type) }}</td></tr>
	<tr><td>{{ _lang('Status') }}</td><td>{!! xss_clean(status($fdrplan->status)) !!}</td></tr>
	<tr><td>{{ _lang('Description') }}</td><td>{{ $fdrplan->description }}</td></tr>
</table>

