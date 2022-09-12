@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title">{{ _lang('Update Payment Gateway') }}</h4>
			</div>
			<div class="card-body">
				<form method="post" class="validate" autocomplete="off" action="{{ action('PaymentGatewayController@update', $id) }}" enctype="multipart/form-data">
					{{ csrf_field()}}
					<input name="_method" type="hidden" value="PATCH">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Name') }}</label>
								<input type="text" class="form-control" name="name" value="{{ $paymentgateway->name }}" required>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Image') }}</label>
								<input type="file" class="form-control dropify" name="image" data-allowed-file-extensions="png jpg" data-default-file="{{ asset('public/backend/images/gateways/'.$paymentgateway->image) }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Status') }}</label>
								<select class="form-control auto-select" data-selected="{{ $paymentgateway->status }}" name="status" id="gateway_status" required>
									<option value="0">{{ _lang('Disable') }}</option>
									<option value="1">{{ _lang('Enable') }}</option>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Currency') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ $paymentgateway->currency }}" id="gateway_currency" name="currency">
									<option value="">{{ _lang('Select One') }}</option>
									@foreach($paymentgateway->supported_currencies as $key => $value)
										<option value="{{ $key }}">{{ $value }}</option>
									@endforeach
								</select>
							</div>
						</div>


						@foreach($paymentgateway->parameters as $key => $value)
							@if($key != 'environment')
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">{{ strtoupper(str_replace('_',' ',$key)) }}</label>
										<input type="text" class="form-control" value="{{ $value }}" name="parameter_value[{{$key}}]">
									</div>
								</div>
							@else
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">{{ strtoupper(str_replace('_',' ',$key)) }}</label>
										<select class="form-control auto-select" data-selected="{{ $value }}" name="parameter_value[{{$key}}]">
											<option value="sandbox">{{ _lang('Sandbox') }}</option>
											<option value="live">{{ _lang('Live') }}</option>
										</select>
									</div>
								</div>
							@endif
						@endforeach

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Exchange Rate') }}</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">1&nbsp;{{ get_base_currency() }}&nbsp;=</span>
									</div>
									<input type="text" class="form-control" name="exchange_rate" id="exchange_rate" value="{{ $paymentgateway->exchange_rate  }}" {{ $paymentgateway->status == 1 ? 'required' : '' }}>
									<div class="input-group-append">
										<span class="input-group-text"><span id="gateway_currency_preview">{{ _lang('Gateway Currency') }}</span></span>
									</div></br>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Minimum Deposit Amount') }}</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">{{ get_base_currency() }}</span>
									</div>
									<input type="text" class="form-control float-field" name="minimum_amount" value="{{ $paymentgateway->minimum_amount }}" required>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Maximum Deposit Amount') }}</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">{{ get_base_currency() }}</span>
									</div>
									<input type="text" class="form-control float-field" name="maximum_amount" value="{{ $paymentgateway->maximum_amount }}" required>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Fixed Charge') }}</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">{{ get_base_currency() }}</span>
									</div>
									<input type="text" class="form-control" name="fixed_charge" value="{{ $paymentgateway->fixed_charge }}" required>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Charge In Percentage') }}</label>
								<div class="input-group mb-3">
									<input type="text" class="form-control" name="charge_in_percentage" value="{{ $paymentgateway->charge_in_percentage }}" required>
									<div class="input-group-append">
										<span class="input-group-text">%</span>
									</div>
								</div>
							</div>
						</div>


						<div class="col-md-12 mt-2">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Save Changes') }}</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


