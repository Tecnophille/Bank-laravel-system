@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		    <div class="card-header">
				<span class="header-title">{{ _lang('Payment Request Details') }}</span>
			</div>

			<div class="card-body">
			    <table class="table table-bordered">
					<tr><td>{{ _lang('Created') }}</td><td>{{ $paymentrequest->created_at }}</td></tr>
					<tr><td>{{ _lang('Currency') }}</td><td>{{ $paymentrequest->currency->name }}</td></tr>
					<tr><td>{{ _lang('Amount') }}</td><td>{{ decimalPlace($paymentrequest->amount, currency($paymentrequest->currency->name)) }}</td></tr>
					<tr><td>{{ _lang('Status') }}</td><td>{!! xss_clean(transaction_status($paymentrequest->status)) !!}</td></tr>
					<tr><td>{{ _lang('Description') }}</td><td>{{ $paymentrequest->description }}</td></tr>
					<tr><td>{{ _lang('Sender') }}</td><td>{{ $paymentrequest->sender->name }} ({{ $paymentrequest->sender->email }})</td></tr>
					<tr><td>{{ _lang('Receiver') }}</td><td>{{ $paymentrequest->receiver->name }} ({{ $paymentrequest->receiver->email }})</td></tr>
			    </table>
			</div>
	    </div>
	</div>
</div>
@endsection
