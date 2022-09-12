@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<span class="panel-title">{{ _lang('Transactions Report') }}</span>
			</div>

			<div class="card-body">

				<div class="report-params">
					<form class="validate" method="post" action="{{ route('reports.transactions_report') }}">
						<div class="row">
              				{{ csrf_field() }}

							<div class="col-md-2">
								<div class="form-group">
									<label class="control-label">{{ _lang('Start Date') }}</label>
									<input type="text" class="form-control datepicker" name="date1" id="date1" value="{{ isset($date1) ? $date1 : old('date1') }}" readOnly="true" required>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label class="control-label">{{ _lang('End Date') }}</label>
									<input type="text" class="form-control datepicker" name="date2" id="date2" value="{{ isset($date2) ? $date2 : old('date2') }}" readOnly="true" required>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
								<label class="control-label">{{ _lang('Transaction Type') }}</label>
									<select class="form-control auto-select" data-selected="{{ isset($transaction_type) ? $transaction_type : old('transaction_type') }}" name="transaction_type">
										<option value="">{{ _lang('All') }}</option>
										<option value="Deposit">{{ _lang('Deposit') }}</option>
										<option value="Withdraw">{{ _lang('Withdraw') }}</option>
										<option value="Wire_Transfer">{{ _lang('Wire Transfer') }}</option>
										<option value="Payment">{{ _lang('Payment') }}</option>
										<option value="Loan">{{ _lang('Loan') }}</option>
                                        <option value="Loan_Repayment">{{ _lang('Loan Repayment') }}</option>
										<option value="Exchange">{{ _lang('Exchange') }}</option>
										<option value="Fixed_Deposit">{{ _lang('Fixed Deposit') }}</option>
									</select>
								</div>
							</div>

                            <div class="col-md-3">
								<div class="form-group">
								<label class="control-label">{{ _lang('Status') }}</label>
									<select class="form-control auto-select" data-selected="{{ isset($status) ? $status : old('status') }}" name="status">
										<option value="">{{ _lang('All') }}</option>
										<option value="1">{{ _lang('Pending') }}</option>
										<option value="2">{{ _lang('Completed') }}</option>
										<option value="0">{{ _lang('Cancelled') }}</option>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<button type="submit" class="btn btn-light btn-sm btn-block mt-26"><i class="icofont-filter"></i> {{ _lang('Filter') }}</button>
							</div>
						</form>

					</div>
				</div><!--End Report param-->

				@php $date_format = get_option('date_format','Y-m-d'); @endphp
				@php $currency = currency(); @endphp

				<div class="report-header">
				   <h4>{{ _lang('Transactions Report') }}</h4>
				   <h5>{{ isset($date1) ? date($date_format, strtotime($date1)).' '._lang('to').' '.date($date_format, strtotime($date2)) : '----------  '._lang('to').'  ----------' }}</h5>
				</div>

				<table class="table table-bordered report-table">
					<thead>
                        <th>{{ _lang('Date') }}</th>
                        <th>{{ _lang('Currency') }}</th>
                        <th>{{ _lang('Amount') }}</th>
                        <th>{{ _lang('Charge') }}</th>
                        <th>{{ _lang('Grand Total') }}</th>
                        <th>{{ _lang('DR/CR') }}</th>
                        <th>{{ _lang('Type') }}</th>
                        <th>{{ _lang('Status') }}</th>
                        <th class="text-center">{{ _lang('Details') }}</th>
					</thead>
					<tbody>
					@if(isset($report_data))
						@foreach($report_data as $transaction)
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
								<td>{!! xss_clean(transaction_status($transaction->status)) !!}</td>
								<td class="text-center"><a href="{{ route('transaction_details', $transaction->id) }}" data-title="{{ _lang('Transaction Details') }}" class="btn btn-outline-primary btn-sm ajax-modal">{{ _lang('View') }}</a></td>
							</tr>
						@endforeach
					@endif
				    </tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection