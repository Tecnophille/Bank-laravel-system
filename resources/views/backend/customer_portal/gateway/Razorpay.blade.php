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
                  <form action="{{ $data->callback_url }}?deposit_id={{ $deposit->id }}" method="POST">
                    @csrf
                    <script
                      src="https://checkout.razorpay.com/v1/checkout.js"
                      data-key="{{ $deposit->gateway->parameters->razorpay_key_id }}"
                      data-amount="{{ convert_currency_2(1, $deposit->gateway->exchange_rate, ($deposit->amount + $deposit->fee) * 100) }}"
                      data-currency="{{ $deposit->gateway->currency }}"
                      data-name="{{ _lang('Deposit Money') }}"
                      data-image="{{ get_logo() }}"
                      data-description="{{ _lang('Deposit Money') }}"
                      data-prefill.name="{{ $deposit->user->name }}"
                      data-prefill.email="{{ $deposit->user->email  }}"
                      data-prefill.contact="{{ $deposit->user->phone  }}"
                      data-notes.shopping_order_id="{{ $deposit->id }}">
                    </script>
                  </form>
              </div>
          </div>
			</div>
		</div>
  </div>
</div>
@endsection