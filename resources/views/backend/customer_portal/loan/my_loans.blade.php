@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header d-flex justify-content-between align-items-center">
				<span class="panel-title">{{ _lang('My Loans') }}</span>
				<a class="btn btn-primary btn-sm float-right" href="{{ route('loans.apply_loan') }}"><i class="icofont-plus-circle"></i> {{ _lang('Apply Loan') }}</a>
			</div>

			<div class="card-body">
				<table id="loans_table" class="table table-bordered data-table">
					<thead>
						<tr>
                            <th>{{ _lang('Loan ID') }}</th>
                            <th>{{ _lang('Loan Product') }}</th>
                            <th>{{ _lang('Currency') }}</th>
                            <th class="text-right">{{ _lang('Applied Amount') }}</th>
                            <th class="text-right">{{ _lang('Total Payable') }}</th>
                            <th class="text-right">{{ _lang('Amount Paid') }}</th>
                            <th class="text-right">{{ _lang('Due Amount') }}</th>
                            <th>{{ _lang('Release Date') }}</th>
                            <th>{{ _lang('Status') }}</th>
						</tr>
					</thead>
					<tbody>
                        @foreach($loans as $loan)
                        <tr>
                            <td><a href="{{ route('loans.loan_details',$loan->id) }}">{{ $loan->loan_id }}</a></td>
                            <td>{{ $loan->loan_product->name }}</td>
                            <td>{{ $loan->currency->name }}</td>
                            <td class="text-right">{{ decimalPlace($loan->applied_amount, currency($loan->currency->name)) }}</td>
                            <td class="text-right">{{ decimalPlace($loan->total_payable, currency($loan->currency->name)) }}</td>
                            <td class="text-right">{{ decimalPlace($loan->total_paid, currency($loan->currency->name)) }}</td>
                            <td class="text-right">{{ decimalPlace($loan->total_payable - $loan->total_paid, currency($loan->currency->name)) }}</td>
                            <td>{{ $loan->release_date }}</td>
                            <td>
                                @if($loan->status == 0)
                                    {!! xss_clean(show_status(_lang('Pending'), 'warning')) !!}
                                @elseif($loan->status == 1)
                                    {!! xss_clean(show_status(_lang('Approved'), 'success')) !!}
                                @elseif($loan->status == 2)
                                    {!! xss_clean(show_status(_lang('Completed'), 'info')) !!}
                                @elseif($loan->status == 3)
                                    {!! xss_clean(show_status(_lang('Cancelled'), 'danger')) !!}
                                @endif
                            </td>
                        </tr>
                        @endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection