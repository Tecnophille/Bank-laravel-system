@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ _lang('Fixed Deposits') }}</span>
				<a class="btn btn-primary btn-sm ml-auto" href="{{ route('fixed_deposits.apply') }}"><i class="icofont-plus-circle"></i> {{ _lang('Apply New') }}</a>
			</div>
			<div class="card-body">
				<table id="fdr_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Plan') }}</th>
							<th>{{ _lang('Currency') }}</th>
							<th>{{ _lang('Deposit Amount') }}</th>
							<th>{{ _lang('Return Amount') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th>{{ _lang('Mature Date') }}</th>
					    </tr>
					</thead>
					<tbody>
                        @foreach($fixedDeposits as $fixed_deposit)
                        <tr>
						    <td>{{ $fixed_deposit->plan->name }}</td>
							<td>{{ $fixed_deposit->currency->name }}</td>
							<td>{{ decimalPlace($fixed_deposit->deposit_amount, currency($fixed_deposit->currency->name)) }}</td>
							<td>{{ decimalPlace($fixed_deposit->return_amount, currency($fixed_deposit->currency->name)) }}</td>
							<td>{!! xss_clean(fdr_status($fixed_deposit->status)) !!}</td>
							<td>{{ $fixed_deposit->mature_date }}</td>
					    </tr>
                        @endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection