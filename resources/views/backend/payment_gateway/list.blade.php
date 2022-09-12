@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h4 class="header-title">{{ _lang('Deposit Gateways') }}</h4>
			</div>
			<div class="card-body">
				<table id="payment_gateways_table" class="table table-bordered data-table">
					<thead>
					    <tr>
							<th>{{ _lang('Image') }}</th>
						    <th>{{ _lang('Name') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($paymentgateways as $paymentgateway)
					    <tr data-id="row_{{ $paymentgateway->id }}">
							<td class='image'><img class="thumb-sm" src="{{ asset('public/backend/images/gateways/'.$paymentgateway->image) }}"/></td>
							<td class='name'>{{ $paymentgateway->name }}</td>
							<td class='status'>{!! xss_clean(status($paymentgateway->status)) !!}</td>

							<td class="text-center">
								<a href="{{ action('PaymentGatewayController@edit', $paymentgateway['id']) }}" class="btn btn-primary btn-sm"><i class="icofont-ui-edit"></i> {{ _lang('Config') }}</a>
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