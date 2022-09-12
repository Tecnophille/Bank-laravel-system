@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title text-center">{{ _lang('Pay Now') }}</h4>
			</div>
			<div class="card-body">
			    <form method="post" class="validate" autocomplete="off" action="{{ route('payment_requests.pay_now', encrypt($paymentrequest->id)) }}">
					{{ csrf_field() }}
					<div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Sender Name') }}</label>
                                <input type="text" class="form-control"  value="{{ $paymentrequest->sender->name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Sender Email') }}</label>
                                <input type="text" class="form-control"  value="{{ $paymentrequest->sender->email }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Request Amount') }}</label>
                                <input type="text" class="form-control float-field" value="{{ decimalPlace($paymentrequest->amount, currency($paymentrequest->currency->name)) }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Charge') }}</label>
                                <input type="text" class="form-control float-field" value="{{ decimalPlace($charge, currency($paymentrequest->currency->name)) }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Grand Total') }}</label>
                                <input type="text" class="form-control float-field" value="{{ decimalPlace(($paymentrequest->amount + $charge), currency($paymentrequest->currency->name)) }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Note') }}</label>
                                <textarea class="form-control" name="note">{{ old('note') }}</textarea>
                            </div>
                        </div>

						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icofont-check-circled"></i> {{ _lang('Confirm') }}</button>
							</div>
						</div>
					</div>
			    </form>
			</div>
		</div>
    </div>
</div>
@endsection