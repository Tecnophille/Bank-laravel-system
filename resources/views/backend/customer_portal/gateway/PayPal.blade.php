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
                            <label class="control-label">{{ _lang('Converted Total') }}</label>
                            <input type="text" class="form-control" name="code" value="{{ decimalPlace(convert_currency_2(1, $deposit->gateway->exchange_rate, $deposit->amount + $deposit->fee), currency($deposit->gateway->currency)) }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="form-group">
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
    </div>
</div>
@endsection

@section('js-script')
<!--PayPal Pay Now Button-->
<script src="https://www.paypal.com/sdk/js?client-id={{ $deposit->gateway->parameters->client_id }}&currency={{ $deposit->gateway->currency }}&disable-funding=credit,card"></script>

<div id="paypal-button-container"></div>

<script>
  paypal.Buttons({
	createOrder: function(data, actions) {
	  	return actions.order.create({
			purchase_units: [{
			  amount: {
				value: '{{ convert_currency_2(1, $deposit->gateway->exchange_rate, $deposit->amount + $deposit->fee) }}'
			  }
			}]
	 	});
	},
	onApprove: function(data, actions) {
		window.location.href = "{{ $data->callback_url }}?order_id=" + data.orderID + "&deposit_id={{ $deposit->id }}";
	},
	onCancel: function (data) {
		alert("{{ _lang('Payment Cancelled') }}");
	}
  }).render('#paypal-button-container');

</script>

@endsection