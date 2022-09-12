@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<span class="panel-title">{{ _lang('View Collateral Details') }}</span>
			</div>

			<div class="card-body">
				<table class="table table-bordered">
					<tr><td>{{ _lang('Name') }}</td><td>{{ $loancollateral->name }}</td></tr>
					<tr><td>{{ _lang('Collateral Type') }}</td><td>{{ $loancollateral->collateral_type }}</td></tr>
					<tr><td>{{ _lang('Serial Number') }}</td><td>{{ $loancollateral->serial_number }}</td></tr>
					<tr>
						<td>{{ _lang('Estimated Price') }}</td>
						<td>{{ decimalPlace($loancollateral->estimated_price, currency($loancollateral->loan->currency->name)) }}</td>
					</tr>
					<tr>
						<td>{{ _lang('Attachments') }}</td>
						<td>
							{!! $loancollateral->attachments == "" ? '' : '<a href="'. asset('public/uploads/media/'.$loancollateral->attachments) .'" target="_blank">'._lang('Download').'</a>' !!}
						</td>
					</tr>
					<tr><td>{{ _lang('Description') }}</td><td>{{ $loancollateral->description }}</td></tr>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection


