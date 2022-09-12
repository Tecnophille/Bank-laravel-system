@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ _lang('Fixed Deposits') }}</span>
				<div class="ml-auto">
					<select name="status" class="select-filter filter-select">
						<option value="">{{ _lang('All') }}</option>
						<option value="0">{{ _lang('Pending') }}</option>
						<option value="1">{{ _lang('Approved') }}</option>
						<option value="2">{{ _lang('Completed') }}</option>
					</select>
					<a class="btn btn-primary btn-sm ml-auto" data-title="{{ _lang('New Fixed Deposit') }}" href="{{ route('fixed_deposits.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
				</div>
			</div>
			<div class="card-body">
				<table id="fdr_table" class="table table-bordered">
					<thead>
					    <tr>
						    <th>{{ _lang('ID') }}</th>
						    <th>{{ _lang('Plan') }}</th>
							<th>{{ _lang('User Account') }}</th>
							<th>{{ _lang('Currency') }}</th>
							<th>{{ _lang('Deposit Amount') }}</th>
							<th>{{ _lang('Return Amount') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th>{{ _lang('Mature Date') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-script')
<script src="{{ asset('public/backend/assets/js/datatables/fixed_deposits.js') }}"></script>
@endsection