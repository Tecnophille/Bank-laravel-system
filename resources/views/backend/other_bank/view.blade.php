@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		    <div class="card-header">
				<span class="header-title">{{ _lang('Bank Details') }}</span>
			</div>

			<div class="card-body">
			    <table class="table table-bordered">
					<tr><td>{{ _lang('Name') }}</td><td>{{ $otherbank->name }}</td></tr>
					<tr><td>{{ _lang('Swift Code') }}</td><td>{{ $otherbank->swift_code }}</td></tr>
					<tr><td>{{ _lang('Bank Country') }}</td><td>{{ $otherbank->bank_country }}</td></tr>
					<tr><td>{{ _lang('Bank Currency') }}</td><td>{{ $otherbank->currency->name }}</td></tr>
					<tr><td>{{ _lang('Minimum Transfer Amount') }}</td><td>{{ currency($otherbank->currency->name).' '.$otherbank->minimum_transfer_amount }}</td></tr>
					<tr><td>{{ _lang('Maximum Transfer Amount') }}</td><td>{{ currency($otherbank->currency->name).' '.$otherbank->maximum_transfer_amount }}</td></tr>
					<tr><td>{{ _lang('Fixed Charge') }}</td><td>{{ currency($otherbank->currency->name).' '.$otherbank->fixed_charge }}</td></tr>
					<tr><td>{{ _lang('Charge In Percentage') }}</td><td>{{ $otherbank->charge_in_percentage.' %' }}</td></tr>
					<tr><td>{{ _lang('Instructions') }}</td><td>{{ $otherbank->descriptions }}</td></tr>
					<tr><td>{{ _lang('Status') }}</td><td>{!! xss_clean(status($otherbank->status)) !!}</td></tr>
			    </table>
			</div>
	    </div>
	</div>
</div>
@endsection


