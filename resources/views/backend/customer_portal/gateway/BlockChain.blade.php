@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-6 offset-lg-3">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title text-center">{{ _lang('Payment Confirm') }}</h4>
			</div>
			<div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">{{ _lang('Amount') }}</label>
                            <input type="text" class="form-control" name="code" value="{{ decimalPlace($deposit->amount, currency())  }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">{{ _lang('Charge') }}</label>
                            <input type="text" class="form-control" name="code" value="{{ decimalPlace($deposit->fee, currency())  }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">{{ _lang('Total') }}</label>
                            <input type="text" class="form-control" name="code" value="{{ decimalPlace($deposit->amount + $deposit->fee, currency()) }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><b>{{ _lang('Send Exact Amount') }}</b></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">BTC</span>
                                </div>
                                <input type="text" class="form-control float-field" value="{{ $data->converted_amount }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">{{ _lang('BTC Address') }}</label>
                            <input type="text" class="form-control" name="code" value="{{ $data->btc_address }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <strong>{{ _lang('SCAN QR CODE') }}</strong>
                            <img src="{{ $data->qr_code }}" alt="{{ $data->btc_address }}">
                        </div>
                    </div>
                </div>
			</div>
		</div>
    </div>
</div>
@endsection