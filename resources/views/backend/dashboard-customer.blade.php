@extends('layouts.app')

@section('content')

	<div class="row">
		@foreach($account_balance as $currency)
		<div class="col-md">
			<div class="card mb-4">
				<div class="card-body">
					<h6>{{ $currency->name.' '._lang('Balance') }}</h6>
					<h6 class="pt-1"><b>{{ decimalPlace($currency->balance, currency($currency->name)) }}</b></h6>
				</div>
			</div>
		</div>
		@endforeach
	</div>

	<div class="row">

		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card h-100 border-bottom-card border-danger">
				<div class="card-body">
					<div class="d-flex">
						<div class="flex-grow-1">
							<h6>{{ _lang('Active Loans') }}</h6>
							<h6 class="pt-1 mb-0"><b>{{ $loans->count() }}</b></h6>
						</div>
						<div>
							<a href="{{ route('loans.my_loans') }}"><i class="icofont-arrow-right"></i>{{ _lang('View') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6  mb-4">
			<div class="card h-100 border-bottom-card border-info">
				<div class="card-body">
					<div class="d-flex">
						<div class="flex-grow-1">
							<h6>{{ _lang('Payment Requests') }}</h6>
							<h6 class="pt-1 mb-0"><b>{{ $payment_request }}</b></h6>
						</div>
						<div>
							<a href="{{ route('payment_requests.index') }}"><i class="icofont-arrow-right"></i>{{ _lang('View') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6  mb-4">
			<div class="card h-100 border-bottom-card border-success">
				<div class="card-body">
					<div class="d-flex">
						<div class="flex-grow-1">
							<h6>{{ _lang('Active Fixed Deposits') }}</h6>
							<h6 class="pt-1 mb-0"><b>{{ $active_fdr }}</b></h6>
						</div>
						<div>
							<a href="{{ route('fixed_deposits.history') }}"><i class="icofont-arrow-right"></i>{{ _lang('View') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6  mb-4">
			<div class="card h-100 border-bottom-card border-info">
				<div class="card-body">
					<div class="d-flex">
						<div class="flex-grow-1">
							<h6>{{ _lang('Active Tickets') }}</h6>
							<h6 class="pt-1 mb-0"><b>{{ $active_tickets }}</b></h6>
						</div>
						<div>
							<a href="{{ route('tickets.my_tickets',['status' => 'active']) }}"><i class="icofont-arrow-right"></i>{{ _lang('View') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="card mb-4">
				<div class="card-header">
					{{ _lang('Up Comming Loan Payment') }}
				</div>
				<div class="card-body">
					<table class="table table-bordered">
						<thead>
							<th>{{ _lang('Loan ID') }}</th>
							<th>{{ _lang('Next Payment Date') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-right">{{ _lang('Amount to Pay') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
						</thead>
						<tbody>
							@if(count($loans) == 0)
								<tr>
									<td colspan="5"><h6 class="text-center">{{ _lang('No Active Loan Available') }}</h6></td>
								</tr>
							@endif

							@foreach($loans as $loan)
							<tr>
								<td>{{ $loan->loan_id }}</td>
								<td>{{ $loan->next_payment->repayment_date }}</td>
								<td>{!! $loan->next_payment->repayment_date >= date('Y-m-d') ? xss_clean(show_status(_lang('Upcoming'),'success')) : xss_clean(show_status(_lang('Due'),'danger')) !!}</td>
								<td class="text-right">{{ decimalPlace($loan->next_payment->amount_to_pay, currency($loan->currency->name)) }}</td>
								<td class="text-center"><a href="{{ route('loans.loan_payment',$loan->id) }}" class="btn btn-primary btn-sm">{{ _lang('Pay Now') }}</a></td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="card mb-4">
				<div class="card-header">
					{{ _lang('Recent Transactions') }}
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>{{ _lang('Date') }}</th>
									<th>{{ _lang('Currency') }}</th>
									<th>{{ _lang('Amount') }}</th>
									<th>{{ _lang('Charge') }}</th>
									<th>{{ _lang('Grand Total') }}</th>
									<th>{{ _lang('DR/CR') }}</th>
									<th>{{ _lang('Type') }}</th>
									<th>{{ _lang('Method') }}</th>
									<th>{{ _lang('Status') }}</th>
								</tr>
							</thead>
							<tbody>
								@foreach($recent_transactions as $transaction)
								@php
								$symbol = $transaction->dr_cr == 'dr' ? '-' : '+';
								$class  = $transaction->dr_cr == 'dr' ? 'text-danger' : 'text-success';
								@endphp
								<tr>
									<td>{{ $transaction->created_at }}</td>
									<td>{{ $transaction->currency->name }}</td>
									@if($transaction->dr_cr == 'dr')
									<td>{{ decimalPlace(($transaction->amount - $transaction->fee), currency($transaction->currency->name)) }}</td>
									@else
									<td>{{ decimalPlace(($transaction->amount + $transaction->fee), currency($transaction->currency->name)) }}</td>
									@endif
									<td>{{ $transaction->dr_cr == 'dr' ? '+ '.decimalPlace($transaction->fee, currency($transaction->currency->name)) : '- '.decimalPlace($transaction->fee, currency($transaction->currency->name)) }}</td>
									<td><span class="{{ $class }}">{{ $symbol.' '.decimalPlace($transaction->amount, currency($transaction->currency->name)) }}</span></td>
									<td>{{ strtoupper($transaction->dr_cr) }}</td>
									<td>{{ str_replace('_',' ',$transaction->type) }}</td>
									<td>{{ $transaction->method }}</td>
									<td>{!! xss_clean(transaction_status($transaction->status)) !!}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
