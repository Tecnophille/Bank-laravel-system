@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title text-center">{{ _lang('Wire Transfer') }}</h4>
			</div>
			<div class="card-body">
			    <form method="post" class="validate" autocomplete="off" action="{{ route('transfer.wire_transfer') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Bank') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ old('bank') }}" name="bank" id="bank" required>
									<option value="">{{ _lang('Select One') }}</option>
									@foreach(\App\Models\OtherBank::where('status',1)->get() as $bank)
									<option value="{{ $bank->id }}">{{ $bank->name }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Swift Code') }}</label>
								<input type="text" class="form-control" id="swift_code" readonly>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Currency') }}</label>
								<input type="text" class="form-control" id="currency" readonly>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Account Number') }}</label>
								<input type="text" class="form-control" name="td[account_number]" value="{{ old('td.account_number') }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Account Holder Name') }}</label>
								<input type="text" class="form-control" name="td[account_holder_name]" value="{{ old('td.account_holder_name') }}" required>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Amount') }}</label>
								<input type="text" class="form-control float-field" name="amount" value="{{ old('amount') }}" required>
								<small id="amount_limit"></small>
								<p class="text-info" id="fee"></p>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Note') }}</label>
								<textarea class="form-control" name="note">{{ old('note') }}</textarea>
							</div>
						</div>

						<div class="col-md-12">
							<h6 id="instructions"></h6>
						</div>

						<div class="col-md-12 mt-4">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icofont-check-circled"></i> {{ _lang('Submit') }}</button>
							</div>
						</div>
					</div>
			    </form>
			</div>
		</div>
    </div>
</div>
@endsection

@section('js-script')
<script>
(function ($) {
  "use strict";
	$(document).on('change','#bank',function(){
		if($(this).val() != ''){
			$.ajax({
				url: "{{ route('transfer.get_other_bank_details') }}" + "/" + $(this).val(),
				success: function(data){
					var currency = data['currency']['name'];
					$("#swift_code").val(data['swift_code']);
					$("#currency").val(currency);
					$("#instructions").html(data['descriptions']);
					$("#fee").html("{{ _lang('Transaction Fee') }} " + currency +' '+ data['fixed_charge'] + " + " + data['charge_in_percentage'] + "%");
					$("#amount_limit").text("{{ _lang('Transfer Limit') }} (" + currency + " " + data['minimum_transfer_amount'] + " - " + currency + " " + data['maximum_transfer_amount'] + ")");
				}
			})
		}
	});
})(jQuery);
</script>
@endsection


