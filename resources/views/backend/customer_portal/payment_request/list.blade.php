@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ _lang('Payment Requests') }}</span>
				<a class="btn btn-primary btn-sm ml-auto" href="{{ route('payment_requests.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
			</div>
			<div class="card-body">
				<table id="payment_requests_table" class="table table-bordered">
					<thead>
					    <tr>
						    <th>{{ _lang('Created') }}</th>
						    <th>{{ _lang('Currency') }}</th>
                            <th>{{ _lang('Amount') }}</th>
                            <th>{{ _lang('Status') }}</th>
                            <th>{{ _lang('Sender') }}</th>
                            <th>{{ _lang('Receiver') }}</th>
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
<script src="{{ asset('public/backend/assets/js/datatables/payment-request.js') }}"></script>
@endsection